<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/3/9
 * Time: 12:28
 */

namespace Home\Controller;


use Think\Controller;

class BaseController extends Controller
{
    protected function req_user_login()
    {
        if (session('?user_id') && session('?username')) {
            return true;
        } else {
            $this->ajaxReturn(qc_json_error('Please login first'));
            return false;
        }
    }
}