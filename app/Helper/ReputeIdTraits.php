<?php
namespace App\Helper;

use App\Models\User;

trait ReputeIdTraits
{

    public function assignIndividualFlowId()
    {
        $this->flow_id = $this->generateFlowId();
        $this->save();
    }

    public function assignCompanyFlowId()
    {
        $this->flow_id = $this->generateFlowId();
        $this->save();
    }

    private function generateFlowId()
    {
        $is_exist_flow_id = false;
        do {
            
            $flowid = rand((int) 1111111111, (int) 9999999999);
           
            return $flowid;
        } while (!$is_exist_flow_id);
    }


    private function checkFlowIDDuplication($flow_id)
    {
        try {
            $is_exist = false;
            if ($flow_id) {
                //It will check if any individuals having this repute id
                $individual_count = User::where("flow_id", $flow_id)->count();
                if ($individual_count > 0) {
                    $is_exist = true;
                }
            }
            return $is_exist;
        } catch (\Exception $e) {
            report($e);
            return $e->getMessage();
        }
    }
}
