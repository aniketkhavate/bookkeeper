<?php

if (! function_exists('successResponse')) {
    function successResponse($message = null, $data = [], $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message ?? "Success",
            'data'    => $data
        ], $status);
    }
}

if (! function_exists('errorResponse')) {
    function errorResponse($message = "", $data = [], $status = 200)
    {
        return response()->json([
            'success' => false,
            'message' => $message ?? "Failed",
            'data'  => $data
        ], $status);
    }
}
