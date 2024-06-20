<?php
namespace App\Helper;

use App\Models\User;

trait ReputeIdTraits
{

    public function assignIndividualReputeId()
    {
        $this->repute_id = $this->generateReputeId();
        $this->save();
    }

    public function assignCompanyReputeId()
    {
        $this->repute_id = $this->generateReputeId();
        $this->save();
    }

    private function generateReputeId()
    {
        $is_exist_repute_id = false;
        do {
            
            $reputeid = rand((int) 1111111111, (int) 9999999999);
           
            return $reputeid;
        } while (!$is_exist_repute_id);
    }


    private function checkReputeIDDuplication($repute_id)
    {
        try {
            $is_exist = false;
            if ($repute_id) {
                //It will check if any individuals having this repute id
                $individual_count = User::where("repute_id", $repute_id)->count();
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
