<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MultivendeController extends Controller
{

    
    private $token;
    private $url;

    public function __construct(){
        $this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJfaWQiOiI5MTA4Nzk2Yi1iMDMxLTRmM2EtOTUyMC1kOGIwNDY2MDg0MTUiLCJzdGF0dXMiOiJjcmVhdGVkIiwiT2F1dGhDbGllbnRJZCI6IjJkMGY2N2MwLWU4NTQtNDEzMi1hNTA4LTdmZWFmM2NkOWU4MCIsIk1lcmNoYW50SWQiOiI4ZjZkYTRlZC0yMWE5LTRlYjAtYjNlNi02NjgxY2E3YTlmMTQiLCJNZXJjaGFudEFwcElkIjoiZTk1NzY4NGMtOTU3Ny00MjFlLThiM2MtZjkwNjdiMmYwMzBkIiwiQ3JlYXRlZEJ5SWQiOiI1NGM1YWM4MC1iYmQzLTRkY2YtOWQ1Zi04YzJiNjBhODg5ZmIiLCJVcGRhdGVkQnlJZCI6IjU0YzVhYzgwLWJiZDMtNGRjZi05ZDVmLThjMmI2MGE4ODlmYiIsIk93bmVySWQiOiI1NGM1YWM4MC1iYmQzLTRkY2YtOWQ1Zi04YzJiNjBhODg5ZmIiLCJleHBpcmVzQXQiOiIyMDIyLTEyLTAxVDIxOjE4OjA2LjAyN1oiLCJyZWZyZXNoVG9rZW4iOiJydC01ZGJlNzBiNS05ZThkLTQ1ODgtODRmOC1jNDNkYmMwOWMxOTEiLCJyZWZyZXNoVG9rZW5FeHBpcmVzQXQiOiIyMDIyLTEyLTAxVDIxOjE4OjA2LjAyN1oiLCJ1cGRhdGVkQXQiOiIyMDIyLTExLTEwVDIxOjE4OjA2LjAwMFoiLCJjcmVhdGVkQXQiOiIyMDIyLTExLTEwVDIxOjE4OjA2LjAwMFoiLCJpYXQiOjE2NjgxMTUwODYsImV4cCI6MTY2OTkyOTQ4Nn0.VP6sN6n1f4_F3JCkYukzeVWUplmnE6JQ_q1I5mXEOH33o7XTylY9DEBz0ob83sYsniilkyzwEthN0sRamnMRG36a_n08Iv48IrhYPLnEoa1TpxPOFSrmovTtbsRyMInBxoe2BzwUK1ht0sRMjzv2VgeotnyVONM4NcN2csvvLWZnc0e6cFUxFidv18rEVeYKWz8ICfSn6QQmtUAeQQLBLR1enu_LB3tFuCaiF8bhn8s6Pp3G59Q4RV9Vrg7dYCO4Lj5UWH3G9nPjQjnSLjlgC8TiVblu4-PfDsQD1yMncZ5OlfMDLERNHUx9HxsuclKk2T78w24pgEHW2XT25JCsE73XQ4SjEJy_Y6m1TJuoDoCMnc52QH1FiVIIbly-NajxE4aXZtf3x1Ouafy18yS4tAqOPGl8u0Fe1WqUBgHIhhIvzaqGPerOw-IFRZFuz686v6V9wLnNtmBYZZC25Zd_z2L3CFgMwwr9gSmEcJaJV4sUVVuwa6kB3sTQnEo8DmDC1zeAK0au8l3_q332TsNj235565VIpzMXo2lyjTNX22YsoZ35dsK_i4CD8Cpx_xWe1p9OlqSfzfqE16-QFwY6uKogSd8AF8sIyF7iqHUwPqnTIQEIR8Wij2YkMi3rpS_xt84g-nSWtGwB_mBWa_WoOmR_ITaEom8vLn3RJ_d2uxg';
        $this->url = 'https://app.multivende.com/';
    }

    public function getRequest(Request $r){
        $input = $r->all();
        $params = http_build_query($input);
        $url = $params != '' ? $this->url.$r->path().'?'.$params : $this->url.$r->path();
        //echo $url;
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
        //return response($r->path());*/
    }

    public function postRequest(Request $r){
        $input = $r->getContent();
        //print_r($input);
        //$params = http_build_query($input);
        $url = $this->url.$r->path();
        //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
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
        //return response($r->path());*/
    }

    public function putRequest(Request $r){
        $input = $r->getContent();
        $url = $this->url.$r->path();
        //echo $url;
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
        //return response($r->path());*/
    }

}
