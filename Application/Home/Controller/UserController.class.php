<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/3/9
 * Time: 12:08
 */

namespace Home\Controller;

use Common\Model\UserModel;
use Think\Controller;
use Think\Upload;

class UserController extends BaseController
{

    public function user_register()
    {
        //(confirm verify)

        $regFields = array('username', 'password', 'sex', 'tel');
        $regData = array();
        foreach ($regFields as $key) {
            $val = I('post.' . $key);
            if (is_invalid_param($val)) {
                $this->ajaxReturn(qc_json_error('Need param: ' . $key));
            }
            if ('password' == $key) {   //md5加密密码
                $val = md5($val);
            }
            $regData[$key] = $val;
        }
        $add = UserModel::add_user($regFields);
        if ($add) {
            $this->ajaxReturn(qc_json_success('Register success'));
        } else {
            $this->ajaxReturn(qc_json_error('Register failed', 40001));
        }
    }

    public function user_login()
    {
        $username = I('post.username');
        $password = I('post.password');

        $confirmData = array('username' => $username, 'password' => md5($password));
        $userModel = new UserModel();
        $res = $userModel->where($confirmData)->find();

        if ($res) {
            session('user_id', $res['id']);
            session('username', $res['username']);
            $this->ajaxReturn(qc_json_success('Login success'));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to login', 40002));
        }
    }

    public function update_userinfo()
    {
        $this->req_user_login();

        $userId = session_id('user_id');
        $updateFields = array('tel', 'sex', 'addr');
        $updateData = null;

        foreach ($updateFields as $key) {
            $val = I('post.' . $key);
            if (!is_invalid_param($val)) {
                $updateData[$key] = $val;
            }
        }
        if (is_null($updateData)) {
            $this->ajaxReturn(qc_json_error('No new update fields', 40002));
        } else {
            $userModel = new UserModel();
            $updateRes = $userModel->where($userId)->save($updateData);
            if ($updateRes) {
                $this->ajaxReturn(qc_json_success('Update success'));
            } else {
                $this->ajaxReturn(qc_json_error('Failed to update user info', 40002));
            }
        }
    }

    public function user_logout()
    {
        $this->req_user_login();
        session('user_id', null);
        session('username', null);
        $this->ajaxReturn(qc_json_success('Logout success.'));
    }

    public function change_password()
    {
        $this->req_user_login();
        $userId = session('user_id');

        $username = session('username');
        $oldPwd = I('post.old_pwd');
        $newPwd = I('post.new_pwd');

        $userModel = new UserModel();
        $res = $userModel->find($userId);

        if ($res['username'] == $username && md5($oldPwd) == $res['password']) {
            if (is_legal_password($newPwd)) {
                $userModel->save(array('password', md5($oldPwd)));
            } else {
                $this->ajaxReturn(qc_json_error('Illegal password.', 40001));
            }
        } else {
            $this->ajaxReturn(qc_json_error('Error old password.', 40001));
        }
    }

    public function update_avatar()
    {
        $this->req_user_login();
        $userId = session('user_id');

        $saveRoot = C('AVATAR_STORE_ROOT');
        $saveRoot = $saveRoot . 'UserAvatar/';
        $uploadFile = new Upload(array('rootPath' => $saveRoot));
        $info = $uploadFile->upload();

        $avatarInfo = $info['avatar'];
        $avatarPath = substr($saveRoot . $avatarInfo['savepath'] . $avatarInfo['savename'], 2);

        //save the path to the database
        $userModel = new UserModel();
        $userModel->find($userId);
        $save = $userModel->save(array('avatar_url' => $avatarPath));
        if ($save) {
            $this->ajaxReturn(qc_json_success('Update success', array('data' => $avatarPath)));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to upload avatar'));
        }
    }

    public function get_user_info()
    {
        $this->req_user_login();
        $username = I('srh_name');
        $userModel = new UserModel();
        if (is_invalid_param($username)) {
            $userId = session_id('user_id');
            $res = $userModel->find($userId);
        } else {
            $res = $userModel->where('username = %s', $username)->find();
        }
        if (!$res) {
            $this->ajaxReturn(qc_json_error('Search error.', 40002));
        } else {
            unset($res['password']);
            $this->ajaxReturn(qc_json_success('Operate success', $res));
        }
    }

    public function wx_user_login()
    {
        //todo wechat user login
    }
}