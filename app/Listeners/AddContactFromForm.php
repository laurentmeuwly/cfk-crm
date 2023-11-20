<?php

namespace App\Listeners;

use App\Events\ContactformCreated;
use App\Models\Contact;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddContactFromForm
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
        $form = $event->contactform;
        $contact = Contact::updateOrCreate(['email' => $form->email], [
            'firstname' => $form->firstname,
            'lastname' => $form->lastname,
            'email' => $form->email,
            'prefered_language' => $form->prefered_language,
            'newsletter' => $form->newsletter,
            'agreement' => $form->agreement,
            'title_id' => $form->title_id,
            'source_id' => $form->source_id
        ]);
    }
}
