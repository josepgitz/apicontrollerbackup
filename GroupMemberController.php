<?php

namespace App\Http\Controllers;

use App\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewGroup($group_id)
    {
        $groups = GroupMember::where('group_id', $group_id);
        $countGroup = $groups->count();
        if($countGroup)
        {
            $members = $groups->get();
            return view('admin.groups.members', ['members' => $members]);
        }else
        {
            return redirect('groups')->with('error_response', 'No members for the chosen group');
        }
    }

    public function approveMember($member_id)
    {
        $update = GroupMember::where('id', $member_id)->first();
        $update->approval_status = '1';
        if($update->save())
        {
            return Redirect::back()->with('success_response', 'Group member successfully approved');
        }else
        {
            return Redirect::back()->with('error_response', 'Fatal error occured while approving a group member');
        }
    }

    public function disapproveMember($member_id)
    {
        $update = GroupMember::where('id', $member_id)->first();
        $update->approval_status = '0';
        if($update->save())
        {
            return Redirect::back()->with('success_response', 'Group member successfully disaapproved');
        }else
        {
            return Redirect::back()->with('error_response', 'Fatal error occured while disaapproving a group member');
        }
    }

    public function deleteMember($member_id)
    {
        $deleteGroup = GroupMember::where('id', $member_id)->delete();
        if($deleteGroup)
        {
            return Redirect::back()->with('success_response', 'Group member successfully deleted');
        }else
        {
            return Redirect::back()->with('error_response', 'Fatal error occured while deleting a group member');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GroupMember  $groupMember
     * @return \Illuminate\Http\Response
     */
    public function show(GroupMember $groupMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroupMember  $groupMember
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupMember $groupMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupMember  $groupMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupMember $groupMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupMember  $groupMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupMember $groupMember)
    {
        //
    }
}
