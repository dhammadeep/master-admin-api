<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\Exceptions\ErrorCode;
// use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public static function respond($code, $data = null,$message='')
    {
        $responseData = [
            "status" => 200,
            'success' => true
        ];

        //Check weather it is an error or success
        if (ErrorCode::is_set($code)) {
            $responseData["success"] = false;
            $responseData["error_code"] = constant("App\\Exceptions\\ErrorCode::$code");
            $responseData["status"] = constant("App\\Exceptions\\ResponseStatus::$code");
        }

        // Handle error message
        if(!empty($message)){
            $responseData["message"] = $message;
        }else{
            $responseData["message"] = constant("App\\Exceptions\\ResponseMessage::$code");
        }
        // Handle data

        $responseData['data'] = $data;



        //Preparing final response
        $response = response()->json($responseData, $responseData["status"]);

        return $response;
    }
}
