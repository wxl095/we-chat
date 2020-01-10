<?php

namespace wxl095\we_chat\core;
class Config
{
    private $appId = 'wx95525cf2481f57b2';
    private $secret = '8e2af19348254b1ccc21caac1454cb30';
    private $token = 'wxl';
    private $redirect_uri = 'https://app1.taxingtianji.com/outdoor_smart_bike/web/html/login.html';

    /**
     * @param string $appId
     */
    public function setAppId(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri;
    }
}