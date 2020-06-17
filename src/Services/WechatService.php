<?php

declare(strict_types = 1);

/**
 * Author: 狂奔的螞蟻 <www.firstphp.com>
 * Date: 2020/6/17
 * Time: 10:22 AM
 */

namespace Firstphp\Wechat\Services;

use Firstphp\Wechat\Bridge\Http;


class WechatService
{


    /**
     * WechatService constructor.
     * @param string $appId
     * @param string $appSecret
     */
    public function __construct(string $appId = '', string $appSecret = '')
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->http = new Http();
    }


    /**
     * 获取access_token
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


}