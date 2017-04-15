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
        $param = I('post.param');
        $filePath = C('FILE_STORE_ROOT') . 'test.jpg';
        $this->base64FileDecode($param, $filePath);
        echo $filePath;
    }
}