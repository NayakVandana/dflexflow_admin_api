<?php

namespace App\Http\Controllers\API;

use App\Enums\CommunicationTemplateType;
use App\Enums\VerificationTokenRequest;
use App\Events\GstFetch;
use App\Events\PanFetch;
use App\Events\User\VerificationToken\VerificationOtpEmail;
use App\Events\User\VerificationToken\VerificationOtpSms;
use App\Http\Controllers\Controller;
use App\Jobs\Otp\SendLoginOtp;
use App\Models\Company;
use App\Models\User;
use App\Models\ProductLog;
use App\Models\VerificationToken;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use App\Models\ProfileWeightageSetting;
use App\Enums\DocumentType;
use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\CommunicationTemplate;
use App\Models\CompanyUserAssociation;
use App\Models\RegistrationData;
use App\Services\GstService;
use App\Services\PanService;
use App\Services\ProfileService;
use Laravolt\Avatar\Avatar;

class UtilityController extends Controller
{

    public static function checkMobileIsRegistered($mobile)
    {
        try {
            $is_mobile_exists = User::where('mobile', $mobile)->where('is_registered', true)->exists();
            if ($is_mobile_exists) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    public function checkMobileExist(Request $request)
    {

        try {

            $validation = Validator::make($request->all(), [
                'mobile' => ['required', 'numeric', 'digits_between:10,12']
            ]);

            if ($validation->fails()) {
                return $this->sendJsonResponse(false, $validation->errors()->first(), $validation->errors()->getMessages(), 200);
            }

            if (UtilityController::checkMobileIsRegistered($request->input('mobile'))) {
                return $this->sendJsonResponse(true, 'User is already registred', [
                    'is_registered' => true,
                ], 200);
            } else {
                return $this->sendJsonResponse(false, 'User not found. Please check your mobile number and try again.', [
                    'is_registered' => false,
                ], 200);
            }
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }
}