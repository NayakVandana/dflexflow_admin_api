<?php

namespace App\Services;

use Carbon\Carbon;
use Directory;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;
use League\Flysystem\MountManager;

class ProfileService
{
    public function generateProfile($entiry, $type)
    {
        try {

            $name = null;

            switch ($type) {               
                case 'user':
                    $name = $entiry->name;
                    $folder_path = $entiry->flow_id . "/profile";
                    $file_name = "/profile.png";
                    break;
            }

            $backgrounds = [
                '#438ACB',
                '#74A4CF',
                '#035198',
                '#8FC4F4',
                '#2E699E',
                '#4A83B6',
                '#2E5D87',
                '#64B5FF',
            ];

            if (!Storage::disk('local')->exists($folder_path)) {
                Storage::disk('local')->makeDirectory($folder_path);
            }

            $avatar = new Avatar();


            $avatar->create(strtoupper($name))
                ->setChars(2)
                ->setBackground($backgrounds[array_rand($backgrounds)])
                ->setShape('square')
                ->setBorder(0, '#fff', 10)
                ->setFontFamily("Inter")
                ->setFontSize(42)
                ->save(storage_path('app/' . $folder_path . $file_name), 100);

            $contents = Storage::disk('local')->get($folder_path . $file_name);

            // Storage::disk('s3')->put($folder_path . $file_name, $contents);

            Storage::disk('local')->put($folder_path . $file_name, $contents);

            // Storage::disk('local')->deleteDirectory($entiry->flow_id);

            return $folder_path . $file_name;
        } catch (Exception $e) {
            return false;
        }
    }
}
