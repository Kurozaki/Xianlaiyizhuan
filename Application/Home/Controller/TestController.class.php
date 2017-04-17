<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/4/15
 * Time: 18:57
 */

namespace Home\Controller;


use Common\Controller\BaseController;

class TestController extends BaseController
{
    public function test()
    {
        $html = http_get("http://yulezibenlun.baijia.baidu.com/article/825927");
    }
}