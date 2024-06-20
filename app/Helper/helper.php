<?php

function formatReputeID($repute_id, $type)
{
    return ($type == "individual" ? "RI-" : "RC-") . substr($repute_id, 0, 4) . "-" . substr($repute_id, 4, 4) . "-" . substr($repute_id, 8, 2);
}

function cleanReputeID($repute_id)
{
    if ($repute_id) {
        return  preg_replace('/[^0-9]/', '', $repute_id);
    }
    return false;
}


function base64_to_jpeg($base64_string, $output_file)
{
    $ifp = fopen($output_file, 'wb');
    fwrite($ifp, base64_decode($base64_string));
    fclose($ifp);
    return $output_file;
}



function execute_curl_admin($sub_url, $fields = [])
{
    $url = config('app.admin_app_api_url') . "/" . $sub_url;

    $client = new \GuzzleHttp\Client();

    $response = $client->request('POST', $url, [
        'headers' => [
            'Authorization' => 'testing',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'form_params' => $fields
    ]);

    $statusCode = $response->getStatusCode();
    $content = json_decode($response->getBody());

    return $content;
}

function execute_curl_production($sub_url, $fields = [])
{

    $url = config('app.production_reputienfo_api_url') . "/" . $sub_url;

    $client = new \GuzzleHttp\Client();

    $response = $client->request('POST', $url, [
        'headers' => [
            'Authorization' => 'testing',
            'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'form_params' => $fields
    ]);

    $statusCode = $response->getStatusCode();
    $content = json_decode($response->getBody());

    return $content;
}

