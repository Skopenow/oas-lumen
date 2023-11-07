<?php

namespace DanBallance\OasLumen\Exceptions;

use Illuminate\Http\JsonResponse;

class JsonErrorFactory
{
    public function make($statusCode, $detail = null) : JsonResponse
    {
        $payload = [
            'title' => $this->getTitle($statusCode),
            'status' => $statusCode
        ];
        if ($detail) {
            $payload['detail'] = $detail;
        }
        return new JsonResponse(
            $payload,
            $statusCode
        );
    }

    protected function getTitle($statusCode)
    {
        switch ($statusCode) {
            case '400':
                return 'Bad Request';
            case '401':
                return 'Unauthorized';
            case '402':
                return 'Payment Required';
            case '403':
                return 'Forbidden';
            case '404':
                return 'Not Found';
            case '405':
                return 'Method Not Allowed';
            case '406':
                return 'Not Acceptable';
            case '407':
                return 'Proxy Authentication Required';
            case '408':
                return 'Request Timeout';
            case '409':
                return 'Conflict';
            case '410':
                return 'Gone';
            case '411':
                return 'Length Required';
            case '412':
                return 'Precondition Failed';
            case '413':
                return 'Payload Too Large';
            default:  // 500
                return 'Internal Server Error';
        }
    }
}
