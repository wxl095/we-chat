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
        $textMessage = new Text();
        $textMessage->reply($this->xmlArray['FromUserName'], $this->xmlArray['ToUserName'], '欢迎关注测试账号');
    }

    public function scan()
    {
        $textMessage = new Text();
        $textMessage->reply($this->xmlArray['FromUserName'], $this->xmlArray['ToUserName'], "<a href='https://www.baidu.com?scene_id=" . $this->xmlArray['EventKey'] . "'>点击参与本次课程</a>");
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