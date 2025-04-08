<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    // Constructor to pass the user and password
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.setpassword')  
            ->with([
                'name' => $this->user->name,
                'password' => $this->password,
            ]);
    }
}

