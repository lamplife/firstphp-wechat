# firstphp-wechat
微信公众号开发


安装扩展:

	composer composer require firstphp/fpwechat


注册服务:

    Firstphp\Fpwechat\Providers\WechatServiceProvider::class


发布配置:

	php artisan vendor:publish


数据表迁移:

    php artisan migrate


编辑.env配置：

    WECHAT_APPID=wxf9958132f63c2902
    WECHAT_APPSECRET=67031cef936714f334b9e69a5c987977
    WECHAT_TOKEN=hedFqtWa7aSbiU3Zfk9WMjhIuNkjFnI2
    WECHAT_AES_KEY=8xArRPLP8wdMdd9HSa1gFfmYBrSFueeSnq4E8nmzZMf

示例代码：

    use Firstphp\Fpwechat\Facades\WechatFactory;

    ......

    $res = WechatFactory::getAccessToken();
    print_r($res);
