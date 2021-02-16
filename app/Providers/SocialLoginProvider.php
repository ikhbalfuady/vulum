<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Laravel\Socialite\Two\InvalidStateException;
use GuzzleHttp\Exception\ClientException;

class SocialLoginProvider extends ServiceProvider
{
 
    static function SocialiteInit($provider) {
		return Socialite::driver($provider)->stateless();
    }
    
    // lo
    static function authenticateProviders($data, $provider = 'google') {

        try {
            $token = $data['access_token'];
            $socialite = SocialLoginProvider::SocialiteInit($provider);
            $user = $socialite->userFromToken($token);
            return $user;
        } catch (InvalidStateException $e) {
            $e = $err->getResponse();
            $res = [
                "status" => $e->getStatusCode(),
                "message" => $err->getMessage(),
            ];
            if(!env('APP_DEBUG'))  $res['message'] = 'Failed Authenticate to ' . $provider;
            return $res;
        } catch (ClientException $err) {
            $e = $err->getResponse();
            $res = [
                "status" => $e->getStatusCode(),
                "message" => $err->getMessage(),
            ];
            if(!env('APP_DEBUG'))  $res['message'] = 'Failed Authenticate to ' . $provider;
            return $res;
        }
       
    }
 
 
}
