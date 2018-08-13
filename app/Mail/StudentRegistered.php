<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Students\Student;
use App\User;
use App\Models\Students\StudentListStore;

class StudentRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $user;
    public $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student, User $user, StudentListStore $store)
    {
        $this->student = $student;
        $this->user = $user;
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nuevo registro / '. $this->store->store_id .' / '. $this->student->name .' '. $this->student->last_name)
            ->markdown('emails.students.registered');
    }
}
