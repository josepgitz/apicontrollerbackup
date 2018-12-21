<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Group;
use App\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('user_type', 0);
        $countUser = $users->count();

        $groups = Group::where('approval_status', 1);
        $countGroup = $groups->count();

        $admin = User::where('user_type', 1);
        $countAdmin = $admin->count();

        return view('admin.index', ['users' => $countUser, 'groups' => $countGroup, 'admins' => $countAdmin]);
    }

    public function validUser()
    {
        $users = User::where('approval_status', 1)->where('user_type', 0)->get();

        return view('admin.users.valid', ['users' => $users]);
    }

    public function inValidUser()
    {
        $users = User::where('approval_status', 0)->where('user_type', 0)->get();

        return view('admin.users.inValid', ['users' => $users]);
    }

    public function approveUser($member_id)
    {
        $update = User::where('id', $member_id)->first();
        $update->approval_status = '1';
        if($update->save())
        {
            return Redirect::back()->with('success_response', 'User successfully approved');
        }else
        {
            return Redirect::back()->with('error_response', 'Fatal error occured while approving a user');
        }
    }

    public function disapproveUser($member_id)
    {
        $update = User::where('id', $member_id)->first();
        $update->approval_status = '0';
        if($update->save())
        {
            return Redirect::back()->with('success_response', 'User successfully disaapproved');
        }else
        {
            return Redirect::back()->with('error_response', 'Fatal error occured while disaapproving a user');
        }
    }

    public function deleteUser($member_id)
    {
        $deleteGroup = User::where('id', $member_id)->delete();
        if($deleteGroup)
        {
            return Redirect::back()->with('success_response', 'User successfully deleted');
        }else
        {
            return Redirect::back()->with('error_response', 'Fatal error occured while deleting a usesr');
        }
    }
}
