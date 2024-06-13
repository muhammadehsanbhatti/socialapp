<?php

namespace App\Services;
// use Srmklive\PayPal\Services\PayPal as Srmk_PayPalClient;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class AwsBucketService
{

    // protected $Paypal_Client;
    // protected $Paypal_Token;

    public function __construct()
    {
        // $this->Paypal_Client = new Srmk_PayPalClient;
        // $this->Paypal_Client->setApiCredentials(config('paypal'));
        // $this->Paypal_Token = $this->Paypal_Client->getAccessToken();
    }

    public function get_files() {
        $files = Storage::disk('s3')->allFiles('images');
        // Retrieve contents of each file if needed
        $fileContents = [];
        foreach ($files as $file) {
            $fileContents[$file] = Storage::disk('s3')->get($file);
        }
        return $fileContents;
    }

    public function store_file($posted_data = []) {

        $file_value = $posted_data['file_value'];
        $file_name = $posted_data['file_name'];


        $imageData = $file_value->storeAs('images',$file_name,'s3');
        $path = $imageData;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = Storage::get( $path );
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );

        return $base64;
    }

    public function list_file($posted_data = []) {

    }

    public function file_details($product_id = 0) {

    }


    public function delete_file($subscription_id = 0) {
        $remove_bucket_file = Storage::disk('s3')->deleteDirectory('images');
        return $remove_bucket_file;
    }
}
