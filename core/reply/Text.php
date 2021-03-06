<?php


namespace wechat\core\reply;


class Text extends Message
{
    protected $xmlStr = '<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%d</CreateTime>
                          <MsgType><![CDATA[text]]></MsgType>
                          <Content><![CDATA[%s]]></Content>
                    </xml>';

    protected function create(string $toUserName, string $fromUserName, $message)
    {
        file_put_contents('./reply.txt', sprintf($this->xmlStr, $toUserName, $fromUserName, time(), $message));
        return sprintf($this->xmlStr, $toUserName, $fromUserName, time(), $message);
    }

    public function reply(string $toUserName, string $fromUserName, $message)
    {
        echo $this->create($toUserName, $fromUserName, $message);
    }
}