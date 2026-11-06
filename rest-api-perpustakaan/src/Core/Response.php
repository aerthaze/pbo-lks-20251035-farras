<?php

declare(strict_types=1);

namespace App\Core;

class Response
{
    /** @param array<string, mixed> $payload */
    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public static function success(string $message, mixed $data = null, int $status = 200): void
    {
        $payload = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        self::json($payload, $status);
    }

    public static function error(string $message, int $status = 400): void
    {
        self::json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
