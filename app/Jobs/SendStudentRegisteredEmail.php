<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;

use App\Mail\StudentRegistered;
use App\Models\Students\Student;
use App\Models\Students\StudentListStore;

class SendStudentRegisteredEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $student;
    public $store;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Student $student, StudentListStore $store)
    {
        $this->student = $student;
        $this->store = $store;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->store->advisor->email)
            ->send(new StudentRegistered($this->student, $this->store->advisor, $this->store));


        //info('Emailed registered student ' . $this->student->name);
    }
}
