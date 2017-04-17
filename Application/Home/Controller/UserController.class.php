<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/17
 * Time: 20:05
 */
namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\UserModel;

class UserController extends BaseController
{
    public function idnCheck()
    {
        $data = $this->reqPost(array('id_number', 'password', 'realname'));
        $realName = $this->getStudentName($data['id_number'], $data['password']);

        if (0 == strcmp($realName, $data['realname'])) {
            session('verify_info', $data);
            $this->ajaxReturn(qc_json_success('Confirm success'));
        } else {
            $this->ajaxReturn(qc_json_error('Wrong student info.'));
        }
    }

    public function userRegister()
    {
        if (session('?verify_info')) {
            $data = session('verify_info');

            $id_number = $data['id_number'];
            $realname = $data['realname'];
//            session('verify_info', null);

            $regData = $this->reqPost(array('tel', 'password', 'pay_pwd'),
                array('qq_num', 'wx_id', 'nickname', 'addr', 'sign'));
            $regData['id_number'] = $id_number;
            $regData['realname'] = $realname;

            if ($this->hasIllegalInfo($regData)) {
                $this->ajaxReturn(qc_json_error('包含非法参数'));
            }
            $model = new UserModel();
            $regRes = $model->regUserInfo($regData);

            $regRes ? $this->ajaxReturn(qc_json_success('Register success')) : $this->ajaxReturn(qc_json_error
            ('Failed to register'));
        } else {
            $this->ajaxReturn(qc_json_error('Please check student info first.'));
        }
    }

    public function userLogin()
    {
        $id_number = I('post.id_number');
        $password = I('post.password');

        $model = new UserModel();
        $info = $model->userLoginConfirm($id_number, $password);
        if (!$info) {
            $this->ajaxReturn(qc_json_error('Wrong password or id number'));
        }
        session('user_idn', $id_number);
        session('user_id', $info['id']);
        $this->ajaxReturn(qc_json_success('Login success'));
    }

    public function userLogout()
    {
        $this->reqLogin();
        session('user_id', null);
        session('user_idn', null);
        $this->ajaxReturn(qc_json_success('Logout success'));
    }


    public function getUserInfo()
    {
        $userId = $this->reqLogin();
        $searchId = intval(I('post.srh_id'));
        $searchId = (0 == $searchId) ? $userId : $searchId;
        $model = new UserModel();
        $info = $model->getUserInfo($searchId);
        if ($info) {
            $this->ajaxReturn(qc_json_success($info));
        } else {
            $this->ajaxReturn(qc_json_error('This user does not exist'));
        }
    }

    public function searchUser()
    {
        $allowKeys = array('id_number', 'realname', 'nickname');
        $key = intval(I('post.key'));
        $val = I('post.val');
        if (!in_array($key, $allowKeys)) {
            $this->ajaxReturn(qc_json_error('Illegal search key'));
        }
        $model = new UserModel();
        $result = $model->where("%s = %s", $key, $val)->select();
        if ($result)
            for ($i = 0; $i < count($result, COUNT_NORMAL); $i++) {
                unset($result[$i]['password']);
                unset($result[$i]['pmsg']);
            }
        $this->ajaxReturn(qc_json_success($result));
    }

    public function updatePassword()
    {
        $userId = $this->reqLogin();
        $data = $this->reqPost(array('oldPwd', 'newPwd'));
        $oldPwd = md5($data['oldPwd']);
        $newPwd = md5($data['newPwd']);

        $model = new UserModel();
        $res = $model->where("id = %d and password = %s", $userId, $oldPwd)->find();
        if (!$res) {
            $this->ajaxReturn(qc_json_error('Wrong password'));
        }
        if (is_password_pattern($newPwd)) {
            $save = $model->save(['password' => $newPwd]);
            if ($save)
                $this->ajaxReturn(qc_json_success('Update success'));
            else
                $this->ajaxReturn(qc_json_error('Failed to update password'));
        } else {
            $this->ajaxReturn(qc_json_error('Illegal password form'));
        }
    }

    public function updateUserInfo()
    {
        $userId = $this->reqLogin();
        $info = $this->reqPost(null, array('qq_num', 'wx_id', 'nickname', 'addr', 'sign'));

        if (count($info, COUNT_NORMAL) == 0) {
            $this->ajaxReturn(qc_json_error('Request at least one param.'));
        }
        if ($this->hasIllegalInfo($info)) {
            $this->ajaxReturn(qc_json_error('包含非法参数'));
        }

        $userModel = new UserModel();
        $save = $userModel->where("id = $userId")->save($info);
        $save ? $this->ajaxReturn(qc_json_success('Update success')) : $this->ajaxReturn(qc_json_error('Failed to
            update user info'));
    }

    public function updateAvatar()
    {
        $userId = $this->reqLogin();
        $uploadInfo = $this->uploadPictures('user_avatar');
        $savePath = $path = C('FILE_STORE_ROOT') . $uploadInfo['savepath'] . $uploadInfo['savename'];
        $savePath = substr($savePath, 2);
        $userModel = new UserModel();
        $save = $userModel->where("id = $userId")->save(['avatar' => $savePath]);
        $save ? $this->ajaxReturn(qc_json_success('Update success')) : $this->ajaxReturn(qc_json_error('Failed to
            update user avatar'));
    }

//    public function test.jpg()
//    {
//        $info = $this->reqPost(array('tel', 'password'));
//        $res = $this->hasIllegalInfo($info);
//        var_dump($res);
//    }
}