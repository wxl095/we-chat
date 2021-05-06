<?php


namespace wechat\core\reply;


abstract class Message
{
    protected $xmlStr;

    protected function create(string &$toUserName, string &$fromUserName, $message)
    {

    }

    public function reply(string &$toUserName, string &$fromUserName, $message)
    {
    }
}