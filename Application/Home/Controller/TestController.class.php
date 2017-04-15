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
        $filePath = C('FILE_STORE_ROOT') . "test/";
        $arr = explode(",", $param, 5);
        foreach ($arr as $val) {
            $path = $filePath . time() * rand() . ".jpg";
            $this->base64FileDecode($val, $path);
            echo "139.199.195.54/xianlaiyizhuan" . substr($path, 1) . "<br>";
        }
    }
}