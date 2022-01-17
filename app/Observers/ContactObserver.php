<?php

namespace App\Observers;

use App\Models\Contact;
use LMeuwly\Sendinblue\SendinblueApi;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function created(Contact $contact)
    {
        if($contact->newsletter && config('sendinblue.apikey')!='') {
            $api = new SendinblueApi(config('sendinblue.apikey'));
            // construct options array to be compatible with Sendinblue
            $title = $contact->title ? $contact->title->name : 'Monsieur';
            $options = ['title' => $title, 'language' => $contact->prefered_language];
            $api->subscribe($contact->email, $contact->firstname, $contact->lastname, $options, 2);
        }

    }

    /**
     * Handle the Contact "updated" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function updated(Contact $contact)
    {
        if($contact->newsletter && config('sendinblue.apikey')!='') {
            $api = new SendinblueApi(config('sendinblue.apikey'));
            // construct options array to be compatible with Sendinblue
            $options = ['title' => $contact->title->name, 'language' => $contact->prefered_language];
            $api->subscribe($contact->email, $contact->firstname, $contact->lastname, $options, 2);
        }
    }

    /**
     * Handle the Contact "deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function deleted(Contact $contact)
    {
        //
    }

    /**
     * Handle the Contact "restored" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function restored(Contact $contact)
    {
        //
    }

    /**
     * Handle the Contact "force deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function forceDeleted(Contact $contact)
    {
        //
    }
}
