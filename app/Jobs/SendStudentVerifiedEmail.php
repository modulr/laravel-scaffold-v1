<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;

use App\Mail\StudentVerified;

use App\Models\Students\Student;
use App\User;

class SendStudentVerifiedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $student;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Student $student, User $user)
    {
        $this->student = $student;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->student->email)
            ->cc($this->user->email)
            ->send(new StudentVerified($this->student));

        //info('Emailed verified student ' . $this->student->name);
    }
}
