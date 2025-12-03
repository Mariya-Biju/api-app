<?php

namespace App\Listeners;

use App\Events\StudentApproved;
use App\Mail\StudentApprovesMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendApprovalEmail
{
    /**
     * Handle the event.
     */
    public function handle(StudentApproved $event)
    {
        Mail::to($event->student->email)->send(
            new StudentApprovesMail($event->student)
        );
    }
}
