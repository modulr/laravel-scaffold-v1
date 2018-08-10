<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Stb\Saucer;
use App\User;

class Reject extends Mailable
{
    use Queueable, SerializesModels;

    public $saucer;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Saucer $saucer, User $user)
    {
        $this->saucer = $saucer;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.stb.reject');
    }
}
