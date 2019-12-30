<?php


namespace wxl095\we_chat\core\reply;


class Text extends Message
{
    protected $xmlStr = "<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%d</CreateTime>
                          <MsgType><![CDATA[text]]></MsgType>
                          <Content><![CDATA[%s]]></Content>
                    </xml>";

    protected function create(string &$toUserName, string &$fromUserName)
    {
        file_put_contents('./reply.txt', sprintf($this->xmlStr, $toUserName, $fromUserName, time(), '欢迎关注测试账号'));
        return sprintf($this->xmlStr, $toUserName, $fromUserName, time(), '欢迎关注测试账号');
    }

    public function reply(string &$toUserName, string &$fromUserName)
    {
        echo $this->create($toUserName, $fromUserName);
    }
}