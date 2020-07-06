<?php

declare(strict_types = 1);

/**
 * Author: 狂奔的螞蟻 <www.firstphp.com>
 * Date: 2020/6/17
 * Time: 10:22 AM
 */

namespace Firstphp\Fpwechat\Services;

use Firstphp\Fpwechat\Bridge\Http;
use Firstphp\Fpwechat\Bridge\MsgCrypt;
use Illuminate\Support\Facades\Request;

class WechatService
{


    protected $appId;
    protected $appSecret;
    protected $token;
    protected $encodingAesKey;
    protected $client;


    /**
     * WechatService constructor.
     * @param string $appId
     * @param string $appSecret
     * @param string $token
     * @param string $encodingAesKey
     */
    public function __construct(string $appId = '', string $appSecret = '', string $token = '', string $encodingAesKey = '')
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;
        $this->encodingAesKey = $encodingAesKey;

        $this->http = new Http();
    }


    /**
     * 获取access_token
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->http->post('cgi-bin/token', [
            'form_params' => [
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'grant_type' => 'client_credential'
            ]
        ]);
    }



    /**
     * 获取模板列表
     *
     * @param string $accessToken
     * @return mixed
     */
    public function getTemplateList(string $accessToken)
    {
        return $this->http->get("cgi-bin/template/get_all_private_template", [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
    }



    /**
     * 发送模板消息
     *
     * @param string $accessToken
     * @param array $data
     * @return mixed
     */
    public function sendTemplateMessage(string $accessToken, array $data)
    {
        return $this->http->post("cgi-bin/message/template/send?access_token=".$accessToken, [
            'json' => $data
        ]);
    }


    /**
     * 发送客服消息
     *
     * @param string $accessToken
     * @param array $message
     * @return mixed
     */
    public function customSend(string $accessToken, array $message)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($message)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($message, JSON_UNESCAPED_UNICODE));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }


    /**
     * 获取推送内容
     *
     * @param string|null $postData
     * @param array $getData
     * @return mixed
     */
    public function getReceiveXml(string $postData = "", array $getData = [])
    {
        $crypter = new MsgCrypt($this->token, $this->encodingAesKey, $this->appId);
        $postData = $postData ? : file_get_contents("php://input");
        $getData = $getData ? : Request::input();
        $result = $crypter->decryptMsg($getData['signature'], $getData['timestamp'], $getData['nonce'], (string)$postData);
        if (isset($result[1])) {
            return $this->fromXml($result[1]);
        }
    }


    /**
     * 获取用户信息
     *
     * @param string $accessToken
     * @param string $openid
     * @return mixed
     */
    public function getUserInfo(string $accessToken, string $openid)
    {
        return $this->http->get("/cgi-bin/user/info", [
            'query' => [
                'access_token' => $accessToken,
                'openid' => $openid,
                'lang' => "zh_CN"
            ]
        ]);
    }



    /**
     * xml 转 array
     *
     * @param string $xml
     * @return mixed
     */
    public function fromXml(string $xml = "") {
        libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->values;
    }

}