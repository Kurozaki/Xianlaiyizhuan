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
        var_dump(([1,2] == false));
    }
}