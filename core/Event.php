<?php


namespace wxl095\we_chat\core;


use wxl095\we_chat\core\reply\Text;

class Event
{
    private $xmlArray = array();

    public function __construct(&$xmlArray)
    {
        $event = strtolower($xmlArray['Event']);
        $this->xmlArray = $xmlArray;
        $this->$event();

    }

    public function subscribe()
    {
        $textMessage = new Text();
        $textMessage->reply($this->xmlArray['FromUserName'], $this->xmlArray['ToUserName'], '欢迎关注测试账号');
        if (isset($this->xmlArray['EventKey'])) {
            $this->scan();
        }
    }

    public function scan()
    {
        file_put_contents('./event.txt', 'scan   ' . json_encode($this->xmlArray));
        $textMessage = new Text();
        $redirect_uri = "https://www.baidu.com";
//        $redirect_uri = urlencode("https://www.baidu.com");

        $textMessage->reply($this->xmlArray['FromUserName'], $this->xmlArray['ToUserName'], "<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx95525cf2481f57b2&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'>点击参与本次课程</a>");
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