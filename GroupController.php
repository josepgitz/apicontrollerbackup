<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\GroupMember;
use Illuminate\Http\Request;
use App\Utilities\RandomNumbers;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groups()
    {
        $groups = Group::get();
        return view('admin.groups.index', ['groups' => $groups]);
    }

    public function approveGroups($group_id)
    {
        $update = Group::where('id', $group_id)->first();
        $update->approval_status = '1';
        if($update->save())
        {
            return redirect('groups')->with('success_response', 'Group successfully approved');
        }else
        {
            return redirect('groups')->with('error_response', 'Fatal error occured while approving a group');
        }
    }

    public function disapproveGroups($group_id)
    {
        $update = Group::where('id', $group_id)->first();
        $update->approval_status = '0';
        if($update->save())
        {
            return redirect('groups')->with('success_response', 'Group successfully disaapproved');
        }else
        {
            return redirect('groups')->with('error_response', 'Fatal error occured while disaapproving a group');
        }
    }

    public function deleteGroups($group_id)
    {
        $deleteGroup = Group::where('id', $group_id)->delete();
        if($deleteGroup)
        {
            return redirect('groups')->with('success_response', 'Group successfully deleted');
        }else
        {
            return redirect('groups')->with('error_response', 'Fatal error occured while deleting a group');
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
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
}
