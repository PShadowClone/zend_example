<?php

class Model_Contactus
{

    public function __construct()
    {

    }

    public $username;
    public $email;
    public $title;
    public $message;


    public function createContactForm($data)
    {
        $contactUs = new _Model_DbTable_Contactus();
        $contactUs->insert($data);
    }
}