<?php

namespace App\Http\Controllers\Apis;

use Auth;
use App\User;
use App\RobotMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RobotController extends Controller
{
    public function tuBot(Request $request)
    {
        $user_id = $request->user_id;
        $content = $request->content;

        if($user_id == "")
        {
            $resp['status'] = '201';
            $resp['message'] = 'user_id is empty';
            $resp['result'] = "";
            return $resp;
        }else
        {
            if($content == "")
            {
                $resp['status'] = '201';
                $resp['message'] = 'content is empty';
                $resp['result'] = "";
                return $resp;
            }else
            {
                $getUser = User::where('id', $user_id)->first();
                if($getUser)
                {
                    if($getUser->approval_status == 1)
                    {
                        $tuBot = new RobotMessage();
                        $tuBot->user_id = $user_id;
                        $tuBot->message = $content;
                        if($tuBot->save())
                        {
                            $resp['status'] = '200';
                            $resp['message'] = 'message sent successfully';
                            $resp['result'] = $tuBot;
                            return $resp;
                        }else
                        {
                            $resp['status'] = '201';
                            $resp['message'] = 'Fatal error occured while sending message to a robbot';
                            $resp['result'] = "";
                            return $resp;
                        }
                    }else
                    {
                        $resp['status'] = '201';
                        $resp['message'] = 'Unfortunately you are deactivated from using our services';
                        $resp['result'] = "";
                        return $resp;
                    }
                }else
                {
                    $resp['status'] = '201';
                    $resp['message'] = 'Unfortunately you are not in our system. Please create account with us';
                    $resp['result'] = "";
                    return $resp;
                }
            }
        }
    }
}
