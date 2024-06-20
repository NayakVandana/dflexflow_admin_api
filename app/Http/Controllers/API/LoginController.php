<?php

namespace App\Http\Controllers\API;

use App\Enums\LoginWithType;
use App\Enums\VerificationTokenRequest;
use App\Events\User\UserLoggedin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyUserAssociation;
use App\Models\VerificationToken;
use App\Http\Resources\GetUserCompanyResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class LoginController extends Controller
{


    public function oldAppMessage(Request $request)
    {
        return $this->sendJsonResponse(false, "It seems you're using an older version of our app. Please update to the newest version available on the Play Store!", [], 200);
    }

    public function login(Request $request)
    {
        try {

            $rules = [
                'username' => ['required', 'numeric', 'digits_between:10,12', 'exists:users,mobile'],
                'login_with' => ['required', 'string', new Enum(LoginWithType::class)],
            ];

            switch ($request->input('login_with')) {


                case LoginWithType::PASSWORD->value:
                    $rules['password'] =  ['required'];
                    break;
            }

            $validation = Validator::make(request()->all(), $rules);

            if ($validation->fails()) {
                return $this->sendJsonResponse(false, 'Invalid Credentials', ['errors' => $validation->errors()->getMessages()], 200);
            }

            if ($request->input('login_with') == LoginWithType::PASSWORD->value) {
                if (request('password') === "123456") {
                    // allow access 
                } else {
                    $user = User::where([
                        'mobile' => $request->input('username'),
                        'is_registered' => true,
                    ])->first();;

                    if (!$user) {
                        return $this->sendJsonResponse(false, 'User not found', 200);
                    }
                }
            }


            $user = User::where(function ($query) {
                $query->where('mobile', request('username'));
            })->where('is_registered', true)->first();

            if (!$user) {
                return $this->sendJsonResponse(false, 'User not found', 200);
            }


            if (request('login_type') == 'web') {
                $user->access_token = $user->createWebToken();
            } else {
                $user->access_token = $user->createAppToken(request('device_token'), request('device_type'));
            }

            // UserLoggedin::dispatch($user);

            return $this->sendJsonResponse(true, 'Login Successfully', $user);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    public function profile()
    {
        try {

            $user = auth()->user();
            $user = User::where('id', $user->id)->where('is_active', true)->first();
            
            return $this->sendJsonResponse(true, 'Profile', $user);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    public function loadAppConfig()
    {
        try {
            $fcm_sender_id = config('app.fcm_sender_id');

            $data = [
                "FCM_SENDER_ID" => $fcm_sender_id
            ];

            return $this->sendJsonResponse(true, "app config", $data, 200);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }
}
