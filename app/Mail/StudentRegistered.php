<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Students\Student;
use App\User;

class StudentRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student, User $user)
    {
        $this->student = $student;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Se ha registrado un participante')
                    ->markdown('emails.students.registered');
    }
}
