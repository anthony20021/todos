<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class SendMailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
        ]);
    }

    static public function SendMailForNotif($request)
    {

        $result = [
            'statut' => 'ok',
            'message' => 'Le message a bien été envoyé'
        ];
        try {
            $data = [
                'from' => 'contact@todos.website',
                'reply_to' => 'contact@todos.website',
                'to' => $request['to'],
                'owner' => $request['owner'],
                'subject' => $request['owner'] . ' vous a partagé sa liste',
                'list_name' => $request['name'],
            ];
                Mail::send('mails.notif', $data, function ($message) use ($data) {
                    $message->subject($data['subject']);
                    $message->from($data['from']);
                    $message->sender($data['from']);
                    $message->replyTo($data['reply_to']);
                    $message->to($data['to']);

                    $message->bcc(['anthony20021@gmail.com'], 'Support info');

                    $message->priority(2);
                });
                return $result;
        } catch (TransportException $e) {
            $result = [
                'statut' => 'ko',
                'message' => $e->getMessage() . ' Line ' .  $e->getLine()
            ];
            return $result;
        }
    }

}
