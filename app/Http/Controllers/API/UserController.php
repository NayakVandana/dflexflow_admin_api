<?php

namespace App\Http\Controllers\API;

use App\Enums\DocumentType;
use App\Enums\VerificationStatus;
use App\Events\User\VerificationToken\VerificationOtpEmail;
use App\Http\Controllers\Controller;
use App\Http\Resources\GetUserAuthorizedCompanyResource;
use App\Http\Resources\GetUserCompanyResource;
use App\Jobs\AutoVerifyGSTDetails;
use App\Models\AadhaarDetail;
use App\Models\AadharDetail;
use App\Models\AccountDeleteRequests;
use App\Models\Address;
use App\Models\Company;
use App\Models\CompanyUserAssociation;
use App\Models\Document;
use App\Models\User;
use App\Models\MobileHistory;
use App\Models\VerificationToken;
use App\Services\AadhaarService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{



    public function viewUserProfile(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'id' => ['nullable', 'numeric', 'exists:users'],
                'flow_id' => ['nullable', 'numeric', 'exists:users']
            ]);

            if ($validation->fails()) {
                return $this->sendJsonResponse(false, 'Invalid data', ['errors' => $validation->errors()->getMessages()], 200);
            }

            if ($request->input('id') == "" && $request->input('flow_id') == "") {
                return $this->sendJsonResponse(false, "Please enter the details", null, 200);
            }

            if ($request->input('id')) {
                $user = User::where('id', $request->input('id'))->first();
            }

            if ($request->input('flow_id')) {
                $user = User::where('flow_id', $request->input('flow_id'))->first();
            }

           

          
            return $this->sendJsonResponse(true, "User Profile Successfully Fetched", [
                'id' => $user->id,
                'user_name' => $user->name,                
                'user_mobile' => $user->mobile,               
                'profile_photo' => $user->photo_url,
                'flow_id' => $user->flow_id,
                'formated_user_flow_id' => $user->formated_flow_id,
                'is_verified' => $user->is_verified,
                
            ], 200);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }
   
    public function updateName(Request $request)
    {

        try {
            $validation = Validator::make($request->all(), [
                'name' => ['required','string','max:255']
            ]);
            if ($validation->fails()) {
                return $this->sendJsonResponse(false, 'Invalid data', ['errors' => $validation->errors()->getMessages()], 200);
            }

            
            
            $user = User::find(auth()->user()->id);
            $user->name = $request->input('name');
            $user->update();

            return $this->sendJsonResponse(true, "Name updated successfully", null, 200);

        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

}