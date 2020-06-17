<?php

declare(strict_types = 1);

/**
 * Author: 狂奔的螞蟻 <www.firstphp.com>
 * Date: 2020/6/17
 * Time: 10:24 AM
 */

namespace Firstphp\Fpwechat\Bridge;

use GuzzleHttp\Client;

class Http
{

    /**
     * base uri
     */
    const BASE_URI = 'https://api.weixin.qq.com/';

    protected $client;
    protected $componentToken;
    protected $componentAppid;
    protected $authorizerToken;

    protected $uploadType;


    /**
     * Http constructor.
     * @param string $baseUri
     */
    public function __construct(string $baseUri = '')
    {
        $this->client = new Client([
            'base_uri' => $baseUri ? $baseUri : static::BASE_URI,
            'timeout' => 200,
            'verify' => false,
        ]);
    }


    /**
     * @param string $componentToken
     */
    public function setComponentToken(string $componentToken)
    {
        $this->componentToken = $componentToken;
    }


    /**
     * @param string $authorizerToken
     */
    public function setAuthorizerToken(string $authorizerToken)
    {
        $this->authorizerToken = $authorizerToken;
    }


    /**
     * @param string $type
     */
    public function setUploadType(string $type)
    {
        $this->uploadType = $type;
    }


    /**
     * @param string $name
     * @param string $arguments
     * @return mixed
     */
    public function __call(string $name, string $arguments)
    {
        if ($this->componentToken) {
            $arguments[0] .= (stripos($arguments[0], '?') ? '&' : '?') . 'component_access_token=' . $this->componentToken;
        }
        if ($this->componentAppid) {
            $arguments[0] .= (stripos($arguments[0], '?') ? '&' : '?') . 'component_appid=' . $this->componentAppid;
        }
        if ($this->authorizerToken) {
            $arguments[0] .= (stripos($arguments[0], '?') ? '&' : '?') . 'access_token=' . $this->authorizerToken;
        }
        if ($this->uploadType) {
            $arguments[0] .= (stripos($arguments[0], '?') ? '&' : '?') . 'type=' . $this->uploadType;
        }

        $response = json_decode($this->client->$name($arguments[0], $arguments[1])->getBody()->getContents(), true);
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            return $response;
        }
        return $response;
    }

}