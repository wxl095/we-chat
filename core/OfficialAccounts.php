<?php

namespace wxl095\we_chat\core;

use ErrorException;
use Redis;
use Exception;

class OfficialAccounts
{
    const ErrorLogFile = __DIR__ . '/OfficialAccountsError.log';
    protected $appId;
    protected $secret;
    protected $token;

    /**
     * OfficialAccounts constructor.
     * @param string $appId
     * @param string $secret
     * @param string $token
     */
    public function __construct(string $appId, string $secret, string $token = 'wxl')
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->token = $token;
    }

    /**
     * signature验证通过原样返回
     * @return string
     */
    public function verification(): string
    {
        if ($this->checkSignature()) {
            return $_GET['echostr'];
        }
    }

    /**
     * 检验signature
     * @return bool
     */
    private function checkSignature(): bool
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取access_token
     * @return string
     * @throws ErrorException
     */
    public function getAccessToken(): string
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1');
        $accessToken = $redis->get($this->appId . '_AccessToken');
        if (!$accessToken) {
            $request = new SendRequest('https://api.weixin.qq.com/cgi-bin/token', ['grant_type' => 'client_credential', 'appid' => $this->appId, 'secret' => $this->secret]);
            $result = $request->post();
            $result = json_decode($result, true);
            if (isset($result['errcode'])) {
                switch ($result['errcode']) {
                    case -1:
                        $this->writeLog('获取access_token失败', '-1', '系统繁忙，此时请开发者稍候再试');
                        break;
                    case 40001:
                        $this->writeLog('获取access_token失败', '40001', 'AppSecret错误或者AppSecret不属于这个公众号，请开发者确认AppSecret的正确性');
                        break;
                    case 40002:
                        $this->writeLog('获取access_token失败', '40002', '请确保grant_type字段值为client_credential');
                        break;
                    case 40164:
                        $this->writeLog('获取access_token失败', '40164', '调用接口的IP地址不在白名单中，请在接口IP白名单中进行设置。');
                        break;
                }
                throw new ErrorException('获取accessToken失败');
            }
            $accessToken = $result['access_token'];
            $redis->set($this->appId . '_AccessToken', $accessToken, 7200);
        }
        return $accessToken;
    }

    /**
     * 创建自定义菜单
     * @param array $menu
     * @return bool
     * @throws ErrorException
     */
    public function createMenu(array $menu)
    {
        $accessToken = $this->getAccessToken();
        $request = new SendRequest("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken", json_encode($menu, JSON_UNESCAPED_UNICODE));

        $result = json_decode($request->post(), true);
        if ($result['errcode'] != 0 && $result['errmsg'] != 'ok') {
            $this->writeLog('自定义菜单创建失败', $result['errcode'], $result['errmsg']);
            return false;
        }
        return true;
    }

    public function responseMessage()
    {
        // 防御XML注入攻击
        libxml_disable_entity_loader(true);
        $xml = file_get_contents("php://input");
        $xmlArray = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        switch ($xmlArray['MsgType']) {
            case 'event':
                new Event($xmlArray);
                break;
        }
    }

    /**
     * 微信服务器返回的错误写入日志
     * @param string $title
     * @param string $errorCode
     * @param string $errorMessage
     */
    private function writeLog(string $title, string $errorCode, string $errorMessage)
    {
        file_put_contents(self::ErrorLogFile, date('Y-m-d H:i:s') . '  appid为：' . $this->appId . '的账号' . $title . '  errorCode:' . $errorCode . "\r\n" . 'errorMessage:' . $errorMessage . "\r\n\r\n");
    }


    /**
     * 永久二维码的整型参数值
     * @param $scene_param
     * @param string $type
     * @return bool|array
     * @throws ErrorException
     * @throws Exception
     */
    public function getQRLimitScene($scene_param, $type = 'int')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s';
        $accessToken = $this->getAccessToken();
        $url = sprintf($url, $accessToken);
        if ($type == 'int') {
            $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": %u}}}';
        }
        if ($type == 'str') {
            $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "%s"}}}';
        }
        $data = sprintf($data, $scene_param);
        $request = new SendRequest($url, $data);
        $result = json_decode($request->post(), true);
        if (isset($result['ticket']) && isset($result['url'])) {
            return $result;
        }
        return false;
    }

    /**
     * 保存二维码
     * @param string $ticket
     * @param string $file_name
     * @param string $save_path
     * @return bool
     * @throws Exception
     */
    public function saveQrCode(string $ticket, string $file_name = '', string $save_path = './')
    {
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s';
        $url = sprintf($url, urlencode($ticket));
        $request = new SendRequest($url);
        $path = '%s%s.jpeg';
        $path = sprintf($path, $save_path, $file_name);
        if (file_put_contents($path, $request->get())) {
            return true;
        } else {
            return false;
        }
    }
}