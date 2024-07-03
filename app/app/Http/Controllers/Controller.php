<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class Controller
{

    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $code
     * @return Response
     */
    protected function success(mixed $data, ?string $message = null, int $code = 200): Response
    {
        $data = json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
        return response($data, $code)->withHeaders([
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length'=> strlen($data),
        ]);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|string|null $data
     * @return Response
     */
    protected function error(?string $message = null, int $code = 500, array|string $data = null): Response
    {
        return $this->success($data, $message, $code);
    }
}
