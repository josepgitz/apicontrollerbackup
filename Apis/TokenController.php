<?php

namespace App\Http\Controllers\Apis;

use Auth;
use App\User;
use App\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function storeToken(Request $request)
    {
        $token = $request->token;
        $user_id = $request->user_id;
        if($token == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'token is empty. Please include it.';
        }else
        {
            try
            {
                if(Token::updateorCreate(['user_id' => $user_id], ['user_id' => $user_id, 'token' => $token]))
                {
                    $results['status'] = '200'; 
                    $results['message'] = 'Token saved successfully.';
                }else
                {
                    $results['status'] = '201'; 
                    $results['message'] = 'Operation not complete.Please try again.';
                }
            }catch(\Exception $e)
            {
                $results['status'] = '201'; 
                $results['message'] = 'Fatal error occured while saving token.';
            }
        }
        return $results;
    }
}
