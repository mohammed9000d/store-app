<?php

namespace App\Http\Controllers;

class ControllerHelper
{
    public static function response($status, $message)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
