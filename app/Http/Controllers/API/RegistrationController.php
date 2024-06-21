<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Events\Company\CompanyRegistred;
use App\Events\User\Registered;
use App\Http\Controllers\Controller;
use App\Models\GstDetail;
use App\Models\User;
use App\Services\GstService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Enums\UserPremission;
use App\Events\User\UserLoggedin;
use App\Models\Address;
use App\Models\Company;
use App\Models\CompanyUserAssociation;
use App\Models\PincodeDetail;
use App\Models\RegistrationData;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function registerUser(Request $request)
    {
        DB::beginTransaction();

        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:5', 'max:100', 'regex:/^[a-zA-Z\s]+$/u'],               
                'mobile' => ['required', 'numeric', 'digits_between:10,12'],              
            ]);

            if ($validation->fails()) {
                return $this->sendJsonResponse(false, $validation->errors()->first(), ['errors' => $validation->errors()->getMessages()], 200);
            }
            $errors = $this->validation(true);
            if ($errors !== false) {
                return $errors;
            }

            if (UtilityController::checkMobileIsRegistered($request->input('mobile'))) {
                return $this->sendJsonResponse(false, 'Mobile number already exists', [
                    'errors' => ['mobile' => 'Mobile number already exists']
                ], 200);
            }        

            $profile_service = app(ProfileService::class);

            $user = new User();
            $user->mobile = $request->input('mobile');
            $user->name = $request->input('name');           
            $user->assignIndividualFlowId();
                        $user->save();

          
            $user->photo = $profile_service->generateProfile((object) $user, 'user');
            $user->update();

            Event::dispatch(new Registered($user)); 

            $user->access_token = $user->createAppToken(request('device_token'), request('device_type'));
            UserLoggedin::dispatch($user);

            DB::commit();

            return $this->sendJsonResponse(true, 'Successfully registred', $user, 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e);
        }
    }

    public function validation($reg = false)
    {
        $error = false;
        $rules = [
            'name' => ['required', 'string', 'min:5', 'max:100', 'regex:/^[a-zA-Z\s]+$/u'],
            'mobile' => ['required', 'numeric', 'digits_between:10,12'],
        ];
        
        $validation = Validator::make(request()->all(), $rules);

        if ($validation->fails()) {
            $error = $this->sendJsonResponse(false, $validation->errors()->first(), ['errors' => $validation->errors()->getMessages()], 200);
        }

        return $error;
    }

    public function checkRegistraionValidation()
    {
        $errors = $this->validation();
        if ($errors === false) {
            return $this->sendJsonResponse(true, 'valid data', null, 201);
        }
        return $errors;
    }
}