<?php

namespace App\Http\Controllers\Apis;

use Auth;
use App\User;
use App\Token;
use App\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $user_id = $request->user_id;
        $receiver = $request->receiver;
        $message = $request->message;
        $attachment = $request->attachment;

        $user_id = $request->user_id;
        if($user_id == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'user_id is empty. Please include it.';
            return $results;
        }else
        {
            if($receiver == "")
            {
                $results['status'] = '201'; 
                $results['message'] = 'receiver is empty. Please include it';
                return $results;
            }else
            {
                if($message == "")
                {
                    $results['status'] = '201'; 
                    $results['message'] = 'message to be is empty';
                    return $results;
                }else
                {
                    $getUser = User::where('id', $user_id)->first();
                    if($getUser)
                    {
                        //GET RECEIVER
                        $getReceiver = User::where('phone', $receiver)->first();
                        if($getReceiver)
                        {
                            $receiver_id = $getReceiver->id;

                            $insMessage = new Message();
                            $insMessage->user_id = $user_id;
                            $insMessage->receiver = $receiver_id;
                            $insMessage->message = $message;

                            if($insMessage->save())
                            {
                                $results['status'] = '200'; 
                                $results['message'] = 'Message sent successfully.';
                                return $results;
                            }else
                            {
                                $results['status'] = '201'; 
                                $results['message'] = 'Fatal error while saving your data';
                                return $results;
                            }
                        }else
                        {
                            $results['status'] = '201'; 
                            $results['message'] = 'The receiver is does not exist in our system. your message content is: '.$message.' ';
                            return $results;
                        }
                    }else
                    {
                        $results['status'] = '201'; 
                        $results['message'] = 'Error!!, Unkown user.';
                        return $results;
                    }
                }
            }
        }
    }

    public function getMessages(Request $request)
    {
        $user_id = $request->user_id;
        if($user_id == "")
        {
            $results['status'] = '201'; 
            $results['message'] = 'user_id is empty. Please include it.';
            $results['results'] = "null";
        }else
        {
            $getUser = User::where('id', $user_id)->first();
            if($getUser)
            {
                $message = Message::where('user_id', $user_id)->orWhere('receiver', $user_id);
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
        return $results;
    }

    public function pushMessage($id, $token, $sender, $receiver, $content)
    {
        define( 'API_ACCESS_KEY', 'AAAAP_RLhro:APA91bEe7jpayeEz8rZKzmuBC7SUWNvuK1X2BqAcrBZVTwkKSxBCxt393g6KcNNf-3mF7R3-_n5rZTZQpWyp6Fyy7gXByu2wGeuh8E3YIbb6lpW0LYI8BKIMjJCPE_GKCsgnJ4ddzJF6' );

        $fields = array
        (
            "registration_ids" => [$token],
            "data" => array(
            'body' => 'You have a new message.',
            'notf_type' => '1',
            'sender' => $sender,
            'receiver' => $receiver,
            'content' => $content
             )
        );
         
        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
         
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        //echo $result; 

        //UPDATE MESSAGE
        $updateMessage = Message::where('id', $id)->first();
        $updateMessage->read_status = '1';
        $updateMessage->save();
    }
    public function deliverMessage()
    {
        $messages = Message::where('read_status', 0);
        $countMessage = $messages->count();
        if($countMessage)
        {
            $getMessages = $messages->get();

            foreach($getMessages as $message)
            {
                $id = $message->id;
                $sender_id = $message->user_id;
                $receiver_id = $message->receiver;
                $content = $message->message;

                //GET TOKEN
                $tokens = Token::where('user_id', $receiver_id)->first();
                if($tokens)
                {
                    //GET RECEIVER DETAILS
                    $receiverDetails = User::where('id', $receiver_id)->first();

                    //GET SENDER DETAILS
                    $senderDetails = User::where('id', $sender_id)->first();

                    $token = $tokens->token;
                    $sender = $senderDetails->phone;
                    $receiver = $receiverDetails->phone;

                    return $this->pushMessage($id, $token, $sender, $receiver, $content);
                }
            }
        }
    }
}
