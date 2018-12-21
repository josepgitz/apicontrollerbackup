<?php

namespace App\Http\Controllers\Apis;

use App\User;
use App\Group;
use App\GroupMember;
use Illuminate\Http\Request;
use App\Utilities\RandomNumbers;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
     public function addGroup(Request $request)
    {
        $name = $request->name;
        $user_id = $request->user_id;
        $link = RandomNumbers::accountCode();
        if($user_id == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'user_id is empty. Please include it.';
        }else
        {
            if($name == "")
            {
                $results['status'] = '201'; 
                $results['message'] = 'name is empty. Please include it.';
            }else
            {
                $getUser = User::where('id', $user_id)->first();
                if($getUser)
                {
                    if($getUser->approval_status == 1)
                    {
                        $addGroup = new Group();
                        $addGroup->user_id = $user_id;
                        $addGroup->name = $name;
                        $addGroup->group_link = $link;
                        if($addGroup->save())
                        {
                            $results['status'] = '200'; 
                            $results['message'] = 'Successfully created an account';
                        }else
                        {
                            $results['status'] = '201'; 
                            $results['message'] = 'Fatal error occured while creating an account';
                        }
                    }else
                    {
                        $results['status'] = '201'; 
                        $results['message'] = 'You are currently deactivated from creating a group';
                    }
                }else
                {
                    $results['status'] = '201'; 
                    $results['message'] = 'Error!!, record not available';
                }
            }
            echo json_encode($results);
        }
    }

    public function getGroup(Request $request)
    {
        $user_id = $request->user_id;
        if($user_id == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'user_id is empty. Please include it.';
            $results['details'] = [];
            echo json_encode($results);
        }else
        {
            $getUser = User::where('id', $user_id)->first();
            if($getUser)
            {
                if($getUser->approval_status == 1)
                {
                    $groups = new Group();
                    $countGroups = $groups->count();
                    if($countGroups)
                    {
                        $getGroups = $groups->get();
                        $results['status'] = '200'; 
                        $results['message'] = 'Groups found';

                        foreach($getGroups as $key => $group)
                        {
                            $results['details'][$key] = [];
                            /*try
                            {*/
                                $results['details'][$key]['name'] = $group->name;
                                $results['details'][$key]['members'] =  $group->groupUsers();
                                $results['details'][$key]['messages'] = $group->groupMessages();
                            /*}catch(\Exception $e)
                            {
                                $results['status'] = '0';
                                $results['message'] = 'Fatal error occured while getting group. Please try again';
                                $results['details'] = [];
                            }*/
                        }
                        echo json_encode($results);
                    }else
                    {
                        $results['status'] = '201'; 
                        $results['message'] = 'No groups for you at the moment';
                        $results['details'] = [];
                        echo json_encode($results);
                    }
                }else
                {
                    $results['status'] = '201'; 
                    $results['message'] = 'You are currently deactivated from creating a group';
                    $results['details'] = [];
                    echo json_encode($results);
                }
            }else
            {
                $results['status'] = '201'; 
                $results['message'] = 'Error!!, record not available';
                $results['details'] = [];
                echo json_encode($results);
            }
        }
    }

    public function sendMessage(Request $request)
    {
        $user_id = $request->user_id;
        $group_id = $request->group_id;
        $content = $request->content;
        if($user_id == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'user_id is empty. Please include it.';
        }else
        {
            if($group_id == "")
            {
                $results['status'] = '201'; 
                $results['message'] = 'group_id is empty. Please include it.';
            }else
            {
                if($content == "")
                {
                    $results['status'] = '201'; 
                    $results['message'] = 'content is empty. Please include it.';
                }else
                {
                    $getUser = User::where('id', $user_id)->first();
                    if($getUser)
                    {
                        if($getUser->approval_status == 1)
                        {
                            $addMessage = new GroupMessage();
                            $addMessage->user_id = $user_id;
                            $addMessage->group_id = $group_id;
                            $addMessage->content = $content;
                            if($addMessage->save())
                            {
                                $results['status'] = '200'; 
                                $results['message'] = 'Successfully sent content';
                            }else
                            {
                                $results['status'] = '201'; 
                                $results['message'] = 'Fatal error occured while creating an account';
                            }
                        }else
                        {
                            $results['status'] = '201'; 
                            $results['message'] = 'You are currently deactivated from creating a group';
                        }
                    }else
                    {
                        $results['status'] = '201'; 
                        $results['message'] = 'Error!!, record not available';
                    }
                }
            }
            echo json_encode($results);
        }
    }
}
