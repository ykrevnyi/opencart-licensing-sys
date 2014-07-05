<?php namespace License\Handlers;


class LicenseHandler {

    
    /**
     * License key was created. We want to send an email to the customer
     *
     * @return void
     */
    public function sendKeyToCustomer($event)
    {
        Log::info('sending new license key to the user.');
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('email.license.created', 'UserEventHandler@sendKeyToCustomer');
    }

}