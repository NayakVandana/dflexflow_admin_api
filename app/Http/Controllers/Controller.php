<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // json response
    public function sendJsonResponse($status = true, $message = null, $data = array(), $status_code = 200)
    {
        return response([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }

    public function sendResultJSON($status, $msg = null, $data = array())
    {
        return response()->json(['result' => $status, 'message' => $msg, 'data' => $data], 200);
    }



    // try catch errors goes here
    public function sendError(\Exception $error)
    {
        report($error);
        if (app()->environment(['local'])) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage(),
            ], 200);
        }
        return response([
            'status' => false,
            'message' => 'something went wrong',
        ], 200);
    }
}
