<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Reply;
use App\Message;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessages(Request $request)
    {
        $user_id = $request->user_id;
        $message_id = $request->message_id;

        $user_id = $request->user_id;
        if($user_id == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'user_id is empty. Please include it.';
            $results['results'] = "null";
        }else
        {
            if($message_id == "")
            {
                $results['status'] = '201'; 
                $results['message'] = 'message_id is empty. Please include it.';
                $results['results'] = "null";
            }else
            {
                $getUser = User::where('id', $user_id)->first();
                if($getUser)
                {
                    $message = Reply::where('message_id', $message_id);
                    $countMessage = $message->count();
                    $getMessages = $message->get();
                    if($countMessage)
                    {
                        $results['status'] = '200'; 
                        $results['message'] = 'Messages found.';
                        $results['results'] = $getMessages;
                    }else
                    {
                        $results['status'] = '201'; 
                        $results['message'] = 'No messages for you at the moment.';
                        $results['results'] = "null";
                    }
                }else
                {
                    $results['status'] = '201'; 
                    $results['message'] = 'Error!!, Unkown user.';
                    $results['results'] = "null";
                }
            }
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
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        //
    }
}
