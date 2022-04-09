<?php

namespace Mcisback\WpPlugin\Http;

class Response {
    public static function sendJsonRaw(array $data, int $statusCode = 200) {
        // remove any string that could create an invalid JSON 
        // such as PHP Notice, Warning, logs...
        ob_clean();

        header('Content-Type: application/json;');

        http_response_code( $statusCode );

        echo json_encode( $data );

        wp_die();
    }

    public static function sendTextRaw(string $str, int $statusCode = 200) {
        // remove any string that could create an invalid JSON 
        // such as PHP Notice, Warning, logs...
        ob_clean();

        //header('Content-Type: application/json;');

        http_response_code( $statusCode );

        echo $str;

        wp_die();
    }

    public static function sendJson(array $data, bool $success=true, int $statusCode = 200) {
        return static::sendJsonRaw([
            'success' => $success,
            'data' => $data,
        ], $statusCode);
    }

    public static function sendJsonSuccess(array $data, int $statusCode = 200) {
        return static::sendJson($data, true, $statusCode);
    }

    public static function sendJsonFailure(array $data, int $statusCode = 200) {
        return static::sendJson($data, false, $statusCode);
    }
}