<?php


namespace wxl095\we_chat\core;


use wxl095\we_chat\core\reply\Text;

class Event
{
    private $xmlArray = array();

    public function __construct($xmlArray)
    {
        $event = strtolower($xmlArray['Event']);
        $this->xmlArray = $xmlArray;
        $this->$event();

    }

    public function subscribe()
    {
        file_put_contents('./debug.txt', date('Y-m-d H:i:s') . json_encode($this->xmlArray));
        $textMessage = new Text();
        $textMessage->reply($this->xmlArray['FromUserName'], $this->xmlArray['ToUserName']);
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