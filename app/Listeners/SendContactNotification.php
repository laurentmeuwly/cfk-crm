<?php

namespace App\Listeners;

use App\Events\ContactformCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class SendContactNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactformCreated $event): void
    {
        //dd($event->contactform);
        //$contacts = ContactForm::where('email_transmitted', '0')->get();

        Mail::to('lolo@lmeuwly.ch')
                ->send(new ContactMail($event->contactform));

        $event->contactform->touch('email_transmitted_at');

    }
}
