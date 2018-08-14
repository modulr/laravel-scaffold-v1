<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Stb\Saucer;

class Approve extends Mailable
{
    use Queueable, SerializesModels;

    public $saucer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Saucer $saucer)
    {
        $this->saucer = $saucer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.stb.approve');
    }
}
