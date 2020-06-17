<?php

declare(strict_types = 1);

/**
 * Author: 狂奔的螞蟻 <www.firstphp.com>
 * Date: 2020/6/17
 * Time: 11:06 AM
 */

namespace Firstphp\Fpwechat\Facades;

use Illuminate\Support\Facades\Facade;


class WechatFactory extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'WechatService';
    }

}