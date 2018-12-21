<?php

namespace App\Http\Controllers\Apis;

use App\Otp;
use App\Sms;
use App\User;
use App\Utilities\RandomNumbers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$phone = $request->phone;
    	if($phone == "")
    	{
    		$results['status'] = '201'; 
	        $results['message'] = 'phone is empty. Please include it.';
            $results['user id']  = "";
    	}else
    	{
	    	$rand_no = RandomNumbers::generatePIN();
	    	try
	    	{
		    	$updateorCreate = User::updateOrCreate([
		    		'phone' => $phone
		    	]);

		        if($updateorCreate)
		        {
		        	$user_id = $updateorCreate->id;

		        	if(Otp::updateOrCreate(['user_id' => $user_id], ['used_status' => '0', 'otp' => $rand_no]))
		        	{
                        //SEND SMS
                        $sendSMS = new Sms();
                        $sendSMS->receiver = $phone;
                        $sendSMS->message = 'Use '.$rand_no.' to verify tuchat account ';
                        if($sendSMS->save())
                        {
                            $results['status'] = '200';
                            $results['message'] = 'Use the code that was sent to you to verify your phone number';
                            $results['user id']  = $user_id;
                        }else
                        {
                            $results['status'] = '201'; 
                            $results['message'] = 'Fatal error occured while sending message.';
                            $results['user id']  = "";
                        }
		        	}else
		        	{
		        		$results['status'] = '201';
		            	$results['message'] = 'opt could not be sent. Please try again';
                        $results['user id']  = "";
		        	}
		        }
		        else
		        {
		            $results['status'] = '201';
		            $results['message'] = 'operation not complete. Please try again';
                    $results['user id']  = "";
		        }
	    	}catch(\Exception $e)
	    	{
	    		$results['status'] = '201'; 
	            $results['message'] = 'Fatal error occured while registering you.';
                $results['user id']  = "";
	    	}
    	}

        echo json_encode($results);
    }

    public function verifyOtp(Request $request)
    {
    	$user_id = $request->user_id;
    	$otp = $request->otp;
    	if($otp == "")
    	{
    		$results['status'] = '201'; 
	        $results['message'] = 'otp is empty.';
            $results['details'] = "null";
    	}else
    	{
    		if($user_id == "")
    		{
    			$results['status'] = '201'; 
	            $results['message'] = 'user_id is empty.';
                $results['details'] = "null";
    		}else
    		{
    			$getUser = User::where('id', $user_id)->first();
    			if($getUser)
    			{
    				$verifyCode = Otp::where('user_id', $user_id)->where('otp', $otp)->first();
    				if($verifyCode)
    				{
    					if($verifyCode->used_status == 0)
    					{
    						$verifyCode->used_status = '1';
    						if($verifyCode->save())
    						{
                                $getUser->approval_status = '1'; 
                                if($getUser->save())
                                {
                                    $results['status'] = '200'; 
                                    $results['message'] = 'Account successfully verified';
                                    $results['details'] = $getUser;
                                }else
                                {
                                    $results['status'] = '201'; 
                                    $results['message'] = 'Error!!, fatal error occured while approving user';
                                    $results['details'] = "null";
                                }
    						}else
    						{
    							$results['status'] = '201'; 
	            				$results['message'] = 'Error!!, fatal error occured while verifying';
                                $results['details'] = "null";
    						}
    					}else
    					{
    						$results['status'] = '201'; 
	            			$results['message'] = 'Error!!, the code has already expired';
                            $results['details'] = "null";
    					}
    				}else
    				{
    					$results['status'] = '201'; 
	            		$results['message'] = 'Error!!, you entered wrong otp. Please try again';
                        $results['details'] = "null";
    				}
    			}else
    			{
    				$results['status'] = '201'; 
	            	$results['message'] = 'Error!!, you dont exist in our system.';
                    $results['details'] = "null";
    			}
    		}
    	}

    	echo json_encode($results);
    }
}
