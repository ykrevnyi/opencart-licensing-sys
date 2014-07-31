<?php namespace License\Handlers;


class LicenseHandler {

    
    /**
     * License key was created. We want to send an email to the customer
     *
     * @return void
     */
    public function sendKeyToCustomer($key, $customerInfo, $module)
    {
        $data = array(
            'key' => $key,
            'info' => $customerInfo,
            'module' => $module
        );
        
        \Mail::send('emails.key-created', $data, function($message)
        {
            $message
                ->from('admin@dev.com', 'Test')
                ->to('yuriikrevnyi@gmail.com', 'Name')
                ->subject('Here your license key!');
        });
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen(
            'email.license.created',
            'License\Handlers\LicenseHandler@sendKeyToCustomer'
        );
    }

}