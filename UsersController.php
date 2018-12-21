<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function getUsers(Request $request)
    {
    	$user_id = $request->user_id; //Auth::id();
    	$phone = $request->phone;

    	if($user_id == "")
    	{
    		$resp['status'] = '201';
    		$resp['message'] = 'user_id is empty. Please include it';
    		$resp['results'] = 'null';
    	}else
    	{
    		if($phone == "")
    		{
    			$resp['status'] = '201';
	    		$resp['message'] = 'phone is empty. Please include it';
	    		$resp['results'] = 'null';
    		}else
    		{
    			$getUsers = User::where('id', $user_id)->first();
    			if($getUsers)
    			{
    				$contacts = User::where('phone', $phone);
    				$countContacts = $contacts->count();
    				if($countContacts)
    				{
    					$getContacts = $contacts->get();

    					$resp['status'] = '200';
			    		$resp['message'] = 'Contacts found';
			    		$resp['results'] = $getContacts;

    				}else
    				{
    					$resp['status'] = '201';
			    		$resp['message'] = 'No contacts for you at the moment';
			    		$resp['results'] = 'null';
    				}
    			}else
    			{
    				$resp['status'] = '201';
		    		$resp['message'] = 'You dont exist in our system. Please create account';
		    		$resp['results'] = 'null';
    			}
    		}
    	}

    	echo json_encode($resp);
    }
}
