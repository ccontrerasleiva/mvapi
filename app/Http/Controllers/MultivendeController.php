<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MultivendeController extends Controller
{
    private $token;
    private $url;



    public function __construct(){
        $user = User::find(1);
        $this->token = $user->current_token;
        $this->url = 'https://app.multivende.com/';
    }


    public function getRequest(Request $r){
        $input = $r->all();
        $params = http_build_query($input);
        $url = $params != '' ? $this->url.$r->path().'?'.$params : $this->url.$r->path();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);   
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return response($result)->header('Content-Type','application/json');
    }

    public function postRequest(Request $r){
        $input = $r->getContent();
        $user = User::find(1);
        $url = $this->url.$r->path();
        $all = $r->all();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        if($r->hasFile('file')) {
            $file = $r->file('file');
            $filename = uniqid($user->id . '_').".".$file->getClientOriginalExtension();
            Storage::disk('public')->put($filename, File::get($file));
            if(Storage::disk('public')->exists($filename)) { 
                $file = new \CurlFile(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$filename, $r->file('file')->getMimeType(), $filename);
                $all['file'] = $file;
                curl_setopt($ch, CURLOPT_POSTFIELDS, $all);
            }
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
        }
        $headers = array();
        $headers[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);   
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return response($result)->header('Content-Type','application/json');
    }

    public function putRequest(Request $r){
        $input = $r->getContent();
        $url = $this->url.$r->path();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($ch, CURLOPT_POSTFIELDS, $input);

        $headers = array();
        $headers[] = 'Authorization: Bearer '.$this->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);   
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return response($result)->header('Content-Type','application/json');
    }

}
