<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/17
 * Time: 19:59
 */
namespace Common\Controller;

use Common\Model\PMsgModel;
use Common\Model\UserModel;
use Think\Controller;
use Think\Upload;

class BaseController extends Controller
{
    protected function reqPost(array $require_data = null, array $unnecessary_data = null)
    {
        if (!IS_POST) {
            $this->ajaxReturn(qc_json_error_request());
        }
        $data = array();
        if ($require_data) {
            foreach ($require_data as $key) {
                $_k = I('post.' . $key, null);
                if (is_null($_k)) {
                    $this->ajaxReturn(qc_json_error_request("require params: " . $key . " value"));
                }
                if (I('post.' . $key) == '') {
                    if (I('post.' . $key) == '')
                        $this->ajaxReturn(qc_json_error_request("必填信息不能为空！"));
                }
                $data[$key] = $_k;
            }
        }
        if ($unnecessary_data) {
            foreach ($unnecessary_data as $key) {
                $_k = I('post.' . $key, null);
                if (!is_null($_k) && '' != $_k) {
                    $data[$key] = $_k;
                }
            }
        }
        return $data;
    }

    protected function reqLogin()
    {
        if (session("?user_id") && session('?user_idn')) {
            return session('user_id');
        }
        $this->ajaxReturn(qc_json_error("No login"));
    }

    protected function getStudentName($id_number, $password)
    {
        define('SOURCE', 'NGID=02ebbcbb-3b27-48ae-df0a-a64aed2804ef; 02ebbcbb-3b27-48ae-df0a-a64aed2804ef=http%3A//jwc.wyu.edu.cn/student/body.htm;');
        define('VALIDATE_URL', 'http://202.192.240.25/student/rndnum.asp');
        define('LOGIN_URL', 'http://202.192.240.25/student/logon.asp');
        define('INFO_URL', 'http://202.192.240.25/student/f1.asp');
        define('HOST', 'jwc.wyu.edu.cn');
        define('ORIGIN', 'http://202.192.240.25');
        define('REFERER', 'http://202.192.240.25/student/');

        $ch = curl_init();
        $response = http_get($ch, VALIDATE_URL, array(
            'Accept:image/webp,image/*,*/*;q=0.8',
            'Accept-Encoding:gzip, deflate, sdch',
            'Connection:keep-alive',
            'Accept-Language:zh-CN,zh;q=0.8,en;q=0.6,pl;q=0.4',
            'Host:' . HOST,
            'Referer:' . REFERER . 'body.htm'
        ));
        $sessionId = substr($response, strpos($response, 'ASPSESSIONID') + 12, 33);
        $code = substr($response, strpos($response, 'LogonNumber=') + 12, 4);
        $response = http_post($ch, LOGIN_URL, [
            'UserCode' => $id_number,
            'UserPwd' => $password,
            'Validate' => $code
        ], [
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding:gzip, deflate',
            'Accept-Language:zh-CN,zh;q=0.8',
            'Connection:keep-alive',
            'Cookie:' . SOURCE . ' ASPSESSIONID' . $sessionId . '; LogonNumber=' . $code,
            'Host:' . HOST,
            'Origin:' . ORIGIN,
            'Referer:' . REFERER . 'body.htm'
        ]);

        $response = http_get($ch, INFO_URL, array(
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding:gzip, deflate, sdch',
            'Accept-Language:zh-CN,zh;q=0.8,en;q=0.6,pl;q=0.4',
            'Connection:keep-alive',
            'Cookie:' . SOURCE . ' ASPSESSIONID' . $sessionId . '; LogonNumber=',
            'Host:' . HOST,
            'Referer:' . REFERER . 'menu.asp'
        ));
        $response = strstr(iconv('gb2312', 'utf-8', $response), '<table');
        $infoPattern = '/<td height="26" width="100" align="center">(.*)+<\/td>/';
        preg_match_all($infoPattern, $response, $arr);
        $stuName = $arr[0][1];
        preg_match('/>(.)+</', $stuName, $stuName);
        $stuName = substr($stuName[0], 1, strlen($stuName[0]) - 2);
        if ($stuName == null || '' == $stuName) {
            $this->ajaxReturn(qc_json_error('Failed to get student name'));
        }

        return $stuName;
    }

    protected function uploadPictures($type, $multiple = false)
    {
        $upload = new Upload(array(
            'rootPath' => C('FILE_STORE_ROOT'),
            'exts' => array('jpg', 'png', 'jpeg')));

        switch ($type) {
            case 'user_avatar':
                $upload->maxSize = 1048576;
                $upload->savePath = 'user/user_avatar/';
                break;

            case 'transact_intro':
                $upload->maxSize = 1048576;
                $upload->savePath = 'transact/transact_intro/';
                break;

            case 'donation':
                $upload->maxSize = 1048576;
                $upload->savePath = 'donation/donation_info/';
                break;

            default:
                echo '$type error.';
                exit;
        }
        $count = 0;
        foreach ($_FILES as &$file) {
            if (is_array($file['name'])) {
                foreach ($file['name'] as &$name) {
                    ++$count;
                    if (pathinfo($name, PATHINFO_EXTENSION) == '') $name .= '.jpg';
                }
            } else {
                ++$count;
                if (pathinfo($file['name'], PATHINFO_EXTENSION) == '') $file['name'] .= '.jpg';
            }
        }
        $info = $upload->upload();                //上传操作 false | array

        if (!$info)
            return $upload->getError();

        $result_data = array();
        foreach ($info as $key => $value) {
            $result_data[] = array(
                'key' => $value['key'],
                'url' => $value['savepath'] . $value['savename']
            );
        }
        if ($multiple) {                          //多图
            $success_count = count($info);
            return array(
                'success_count' => $success_count,                //上传成功的张数
                'error_count' => $count - $success_count,       //上传失败的张数
                'success_array' => $result_data,                  //上传成功的信息数组
                'error_msg' => $upload->getError()            //错误信息
            );
        } else {                                    //单图
            reset($info);
            return current($info);
        }
    }

    protected function sendSystemMsgToUser($content, $receiver)
    {
        $pModel = new PMsgModel();
        $pModel->sendPM(-1, $receiver, $content, 1);
    }

    protected function reqUserWithPermission($userId, $permission)
    {
        $userModel = new UserModel();
        $find = $userModel->where("id = %d and perm = %d", $userId, $permission)->find();
        if (!$find) {
            $this->ajaxReturn(qc_json_error_request('No permission'));
        }
    }

    protected function hasIllegalInfo($info)
    {
        $patternArr = array(
            'tel' => '/^1[0-9]{10}$/',
            'password' => '/^[a-zA-Z0-9]{6,20}$/',
            'pay_pwd' => '/^[a-zA-Z0-9]{6,10}$/',
            'qq_num' => '/^[0-9]{6,15}$/',
            'wx_id' => '/^[a-zA-Z0-9]{6,20}$/'
        );
        return !regex_confirm_patterns($info, $patternArr);
    }

    protected function base64FileDecode($base64, $path)
    {
        $file = fopen($path, 'w');
        $flag = fwrite($file, base64_decode($base64));
        fclose($file);
        return $flag;
    }

}