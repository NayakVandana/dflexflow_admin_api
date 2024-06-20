<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\CompanyUserAssociation;
use Exception;

class DepartmentController extends Controller
{

    public function saveDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:2', 'max:100'],
                'is_active' => ['required', 'boolean'],
            ]);
            if ($validation->fails()) {
                return $this->sendJsonResponse(false, 'Invalid data', ['errors' => $validation->errors()->getMessages()], 200);
            }

            if (($request->input('id'))) {

                $find_department = Department::where([
                   
                    'id' => $request->input('id'),
                ])->first();

                $department = $find_department ? Department::find($request->input('id')) : null;

                if (!$department) {
                    return $this->sendJsonResponse(false, "Department not found with specified ID", null, 200);
                }
            } else {
                $department = new Department();
            }

            $department->name = $request->input('name');
            $department->is_active = $request->input('is_active');
            $department->user_id = auth()->user()->id;
            $department->save();

            DB::commit();

            return $this->sendJsonResponse(true, "Department " . ($request->input('id') ? "Updated" :  'Added') . ' Successfully', null, 201);
        } catch (Exception $e) {

            DB::rollBack();
            return $this->sendError($e);
        }
    }

    public function getDepartment()
    {
        try {
            $departments_with_subdepartments = Department::where("user_id", auth()->user()->id)->get();
               
            return $this->sendJsonResponse(true, 'fetch department list',  $departments_with_subdepartments, 200);
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    public function deleteDepartment(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'id' => ['required'],
            ]);

            if ($validation->fails()) {
                return $this->sendJsonResponse(false, 'Invalid data', ['errors' => $validation->errors()->getMessages()], 200);
            }

            $delete_department = Department::where('id', $request->input('id'))->first();
            $delete_department->delete();
          
            return $this->sendJsonResponse(false, "Department deleted successfully", null, 200);
            
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }
}