<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Auth;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $token = Uuid::uuid1()->toString() . '-' . Uuid::uuid4()->toString();
        $name = explode(" ",$this->user->name);
        $this->user->token = $token;
        $this->user->save();
        return $this->from('noreply@interacsms.com', 'Interac SMS')
                    ->view('mails.email_verify')
                    ->with(
                        [
                              'token' => $token,
                              'name' => $name[0]
                        ]);
    }
}
