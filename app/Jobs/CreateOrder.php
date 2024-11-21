<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class CreateOrder implements ShouldQueue
{
    use Queueable;

    private $id;
    private $email;

    /**
     * Create a new job instance.
     */
    public function __construct($id , $email)
    {
        //
        $this->id = $id;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new \App\Mail\CreateOrder($this->id));
    }
}
