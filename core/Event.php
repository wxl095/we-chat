<?php


namespace wxl095\we_chat\core;


use wxl095\we_chat\core\reply\Text;

class Event
{
    protected $xmlArray = array();

    public function __construct(&$xmlArray)
    {
        $event = strtolower($xmlArray['Event']);
        $this->xmlArray = $xmlArray;
        $this->$event();

    }

    public function subscribe()
    {
        $textMessage = new Text();
        $message = '欢迎关注户外智能单车';
        if (isset($this->xmlArray['EventKey']) && isset($this->xmlArray['Ticket'])) {
            $redirect_uri = "https://www.baidu.com";

            $message .= "\r\n<a href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx95525cf2481f57b2&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect'>点击参与本次课程</a>";
        }
        $textMessage->reply($this->xmlArray['FromUserName'], $this->xmlArray['ToUserName'], $message);

    }

    public function scan()
    {
        file_put_contents('./event.txt', 'scan   ' . json_encode($this->xmlArray));
        $textMessage = new Text();
        $redirect_uri = "https://www.baidu.com";
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