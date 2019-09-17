<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessagesSent extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function build()
    {
        $name = explode(" ",$this->data->name);
        return $this->from('noreply@interacsms.com', 'Interac SMS')
                    ->view('mails.messages_sent')
                    ->with(
                        [
                              'name' => $name[0]
                        ]);
    }
}
