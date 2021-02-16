<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Exception;

class HelperProvider extends ServiceProvider
{
    
    /*
        default res geoip : 
        {
            "ip":"114.124.168.197",
            "country_code":"ID",
            "country_name":"Indonesia",
            "region_code":"JK",
            "region_name":"Jakarta",
            "city":"Jakarta",
            "zip_code":"",
            "time_zone":"Asia/Jakarta",
            "latitude":-6.1741,
            "longitude":106.8296,
            "metro_code":0
        }
    */
    public static function geoip($ip) {
        try {
            $url = 'https://freegeoip.app/json/'.$ip;
            $response = Http::get($url);
            $status = $response->status();
            if ($status == 200) {
                return $response->json();
            } else return null;
        } catch (Exception $e) {
            return $e->getMessage();
        }
      
    }

   public static function isNoData($request, $data) {
        $payload = $request->all();
        if (H_hasRequest($payload, 'table')) $null_info = H_isNullList($data);
        else $null_info = H_isNullArray($data);

        return $null_info;
   }


}
