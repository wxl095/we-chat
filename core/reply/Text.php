<?php


namespace wxl095\we_chat\core\reply;


class Text extends Message
{
    private $xmlStr = "<xml>
                          <ToUserName><![CDATA[%s]]></ToUserName>
                          <FromUserName><![CDATA[%s]]></FromUserName>
                          <CreateTime>%d</CreateTime>
                          <MsgType><![CDATA[text]]></MsgType>
                          <Content><![CDATA[%s]]></Content>
                    </xml>";

    protected function create()
    {
        return sprintf($this->xmlStr, 'oOlpbwGxscJ-q-fL9YhnZHoo0VCw', 'wx95525cf2481f57b2', time(), '欢迎关注测试账号');
    }

    public function reply()
    {
        echo $this->create();
    }
}