<?php
namespace wxl095\we_chat\core;
/**
 * Created by PhpStorm
 * User: wxl
 * Date: 19-7-19
 * Time: 上午11:04
 */

class SendRequest
{
	private $request;
	CONST TIMEOUT = 5;
	protected $data;
	protected $url;

	/**
	 * SendRequest constructor.
	 * @param string $url
	 * @param  $data
	 * @throws Exception
	 */
	public function __construct(string $url, $data='')
	{
		$this->request = curl_init();
		if (empty($url)) throw new Exception('url地址不能为空');
		$this->data = $data;
		$this->url = $url;
	}

	private function setRequest()
	{
		//在输出中不包含标头。
		curl_setopt($this->request, CURLOPT_HEADER, false);
		//false阻止CURL验证对等方的证书。可以使用该CURLOPT_CAINFO选项指定要验证的备用证书，也可以使用该选项指定证书目录 CURLOPT_CAPATH。
		curl_setopt($this->request, CURLOPT_SSL_VERIFYPEER, false);
		//1检查SSL对等证书中是否存在公用名。
		//2检查是否存在公用名，并验证它是否与提供的主机名匹配。
		//0不检查名称。在生产环境中，此选项的值应保持为2（默认值）。
		curl_setopt($this->request, CURLOPT_SSL_VERIFYHOST, 0);
		//TRUE将传输作为curl_exec（）的返回值的字符串返回，而不是直接输出。
		curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
		//	允许cURL函数执行的最大秒数。
		curl_setopt($this->request, CURLOPT_TIMEOUT, self::TIMEOUT);
	}

	public function post()
	{
		$this->setRequest();
		//要获取的URL。使用curl_init（）初始化会话时也可以设置此项。
		curl_setopt($this->request, CURLOPT_URL, $this->url);
		//POST请求
		curl_setopt($this->request, CURLOPT_POST, true);
		//要在HTTP“POST”操作中发布的完整数据
		if (is_array($this->data)) $this->data = http_build_query($this->data);
		curl_setopt($this->request, CURLOPT_POSTFIELDS, $this->data);
		return $this->exec();
	}

	public function get()
	{
		$this->setRequest();
		//要获取的URL。使用curl_init（）初始化会话时也可以设置此项。
		curl_setopt($this->request, CURLOPT_URL, $this->url . http_build_query($this->data));
		return $this->exec();
	}

	protected function exec()
	{
		return curl_exec($this->request);
	}

	public function __destruct()
	{
		curl_close($this->request);
	}
}