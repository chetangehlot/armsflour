<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'third_party/twilio/Twilio/autoload.php';

use Twilio\Rest\Client;
use Twilio\TwiML;  // TwiML used

class Btwilio {

    private $btwilio;

    public function __construct() {
        $AccountSid = "AC7fb17180c8347399dc6a31cbc7b56d57";
        $AuthToken = "aa3e36de341500f5e913e90ee9ae83bb";



        $this->btwilio = new Client($AccountSid, $AuthToken);

        $this->btwiml = new Twiml();
    }

    public function sendsms() {

        $people = array(
            "+919048XXXXXX" => "Rajeev"
        );

        // Step 5: Loop over all our friends. $number is a phone number above, and 
        // $name is the name next to it
        foreach ($people as $number => $name) {

            $sms = $this->btwilio->account->messages->create(
                    // the number we are sending to - Any phone number
                    $number, array(
                // Step 6: Change the 'From' number below to be a valid Twilio number 
                // that you've purchased
                'from' => "+18559063122",
                // the sms body
                'body' => "Hey $name, Monkey Party at 6PM. Bring Bananas!"
                    )
            );

            // Display a confirmation message on the screen
            echo "Sent message to $name";
        }
    }

    public function call() {
        $this->btwilio->calls->create(
               '+917987050092','+1 315-870-1697' , array(
            "url" => "http://demo.twilio.com/docs/voice.xml"
                )
        );
    }

    public function sendtwiml() {

        // $this->btwiml  = new Twiml();  use $this->btwiml to use Twiml services
        // Step 5: Loop over all our friends. $number is a phone number above, and 
        // $name is the name next to it
        //$response = new Twiml();
        $this->btwiml->sms('The king stay the king.', ['from' => '+14105551234',
            'to' => '+917987050092']);

        print_r($this->btwiml);
    }

}
