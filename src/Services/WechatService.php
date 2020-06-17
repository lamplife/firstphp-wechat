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
     */
    public function __construct(string $appId = '', string $appSecret = '', string $token='', string $encodingAesKey='')
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
        return $this->http->get("cgi-bin/template/get_all_private_template?access_token={$accessToken}", [
            'form_params' => []
        ]);
    }



    /**
     * 发送模板消息
     *
     * @param string $accessToken
     * @param array $data
     * @return mixed
     */
    public function sendMessage(string $accessToken, array $data)
    {
        return $this->http->get("cgi-bin/message/template/send?access_token={$accessToken}", [
            'form_params' => [

            ]
        ]);
    }


    /**
     * 获取推送内容
     */
    public function getReceiveXml($postData = null, $getData = null)
    {
        $crypter = new MsgCrypt($this->token, $this->encodingAesKey, $this->appId);
        $postData = $postData ? : file_get_contents("php://input");
        $getData = $getData ? : Request::input();
        $result = $crypter->decryptMsg($getData['msg_signature'], $getData['timestamp'], $getData['nonce'], $postData);
        return $result;
    }

    

}