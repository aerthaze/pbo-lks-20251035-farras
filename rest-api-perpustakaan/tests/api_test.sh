#!/usr/bin/env bash

set -u

BASE_URL="${BASE_URL:-http://localhost:8000/api}"
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

if [ -n "${JQ_BIN:-}" ]; then
    JQ="$JQ_BIN"
elif command -v jq >/dev/null 2>&1; then
    JQ="jq"
elif [ -x "$SCRIPT_DIR/../tools/jq.exe" ]; then
    JQ="$SCRIPT_DIR/../tools/jq.exe"
else
    echo "jq tidak ditemukan. Pasang jq atau letakkan jq.exe di folder tools/."
    exit 1
fi

TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

PASS=0
FAIL=0

assert_status() {
    local expected="$1"
    local actual="$2"
    local label="$3"

    if [ "$actual" = "$expected" ]; then
        echo "PASS  $label (HTTP $actual)"
        PASS=$((PASS + 1))
    else
        echo "FAIL  $label (diharapkan HTTP $expected, dapat HTTP $actual)"
        FAIL=$((FAIL + 1))
    fi
}

assert_json() {
    local expression="$1"
    local file="$2"
    local label="$3"

    if "$JQ" -e "$expression" "$file" >/dev/null 2>&1; then
        echo "PASS  $label"
        PASS=$((PASS + 1))
    else
        echo "FAIL  $label"
        FAIL=$((FAIL + 1))
    fi
}

request() {
    local method="$1"
    local path="$2"
    local data="$3"
    local output="$4"

    if [ -n "$data" ]; then
        curl -s -o "$output" -w "%{http_code}" \
            -X "$method" "$BASE_URL$path" \
            -H "Content-Type: application/json" \
            -d "$data"
    else
        curl -s -o "$output" -w "%{http_code}" -X "$method" "$BASE_URL$path"
    fi
}

echo "=== Uji Otomatis REST API Perpustakaan ==="
echo "Base URL: $BASE_URL"
echo

# 1. GET semua buku
STATUS="$(request GET "/books" "" "$TMP_DIR/all.json")"
assert_status 200 "$STATUS" "GET semua buku"
assert_json '.success == true and (.data | type == "array")' "$TMP_DIR/all.json" "Response daftar buku valid"

# 2. POST buku baru
POST_DATA='{"judul":"Bumi","penulis":"Tere Liye","tahun":2014,"stok":2}'
STATUS="$(request POST "/books" "$POST_DATA" "$TMP_DIR/post.json")"
assert_status 201 "$STATUS" "POST buku baru"
assert_json '.success == true and .data.judul == "Bumi"' "$TMP_DIR/post.json" "Data buku baru dikembalikan"

NEW_ID="$("$JQ" -r '.data.id' "$TMP_DIR/post.json" 2>/dev/null)"
if [ -z "$NEW_ID" ] || [ "$NEW_ID" = "null" ]; then
    echo "FAIL  ID buku baru tidak ditemukan; pengujian berikutnya dilewati"
    FAIL=$((FAIL + 1))
else
    # 3. GET buku berdasarkan ID hasil POST
    STATUS="$(request GET "/books/$NEW_ID" "" "$TMP_DIR/detail.json")"
    assert_status 200 "$STATUS" "GET buku ID $NEW_ID"
    assert_json ".success == true and .data.id == $NEW_ID" "$TMP_DIR/detail.json" "Detail buku sesuai ID"

    # 4. PUT mengubah buku
    PUT_DATA='{"judul":"Bumi Revisi","penulis":"Tere Liye","tahun":2015,"stok":7}'
    STATUS="$(request PUT "/books/$NEW_ID" "$PUT_DATA" "$TMP_DIR/put.json")"
    assert_status 200 "$STATUS" "PUT buku ID $NEW_ID"
    assert_json '.success == true and .data.judul == "Bumi Revisi"' "$TMP_DIR/put.json" "Data buku berubah"

    # 5. DELETE buku
    STATUS="$(request DELETE "/books/$NEW_ID" "" "$TMP_DIR/delete.json")"
    assert_status 200 "$STATUS" "DELETE buku ID $NEW_ID"
fi

# 6. Validasi error untuk judul kosong
STATUS="$(request POST "/books" '{"judul":""}' "$TMP_DIR/invalid.json")"
assert_status 422 "$STATUS" "Validasi judul kosong"
assert_json '.success == false and .message == "Judul wajib diisi"' "$TMP_DIR/invalid.json" "Pesan validasi sesuai"

echo
echo "=== Hasil: $PASS lulus, $FAIL gagal ==="

if [ "$FAIL" -gt 0 ]; then
    exit 1
fi
