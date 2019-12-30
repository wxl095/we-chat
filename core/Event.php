<?php


namespace wxl095\we_chat\core;


use wxl095\we_chat\core\reply\Text;

class Event
{
    private $xmlObject = null;

    public function __construct($xmlObject)
    {
        $event = strtolower($xmlObject->Event);
        $this->xmlObject = $xmlObject;
        $this->$event();
    }

    public function subscribe()
    {
        $textMessage = new Text();
        $textMessage->reply();
    }

    public function scan()
    {

    }

    public function unsubscribe()
    {

    }

    public function location()
    {

    }

    public function click()
    {

    }
}