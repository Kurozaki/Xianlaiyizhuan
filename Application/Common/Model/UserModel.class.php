<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/14
 * Time: 16:58
 */

namespace Common\Model;


class UserModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('user', $this->tablePrefix, $this->connection);
    }

    public function userLoginConfirm($id_number, $password)
    {
        $idn_pattern = "/^[\\d]{10}$/";
        $password = md5($password);
        if (preg_match($idn_pattern, $id_number)) {
            $find = $this->where("id_number = '$id_number' and password = '$password'")->find();
            return $find;
        } else {
            return false;
        }
    }

    public function payPasswordConfirm($userId, $payPwd)
    {
        $payPwd = md5($payPwd);
        $find = $this->where(array('id' => $userId, 'pay_pwd' => $payPwd))->find();
        return $find;
    }

    public function regUserInfo($userInfo)
    {
        $userInfo['password'] = md5($userInfo['password']);
        $userInfo['pay_pwd'] = md5($userInfo['pay_pwd']);
        return $this->add($userInfo);
    }

    public function addPMsgNotice($userId, $step = 1)
    {
        $data = $this->where("id = %d", $userId)->find();
        if ($data) {
            $_msg = $data['pmsg'];
            $_msg = intval($_msg) + $step;
            S('PMsg_uid_' . $userId, $_msg, 7200);  //put into cache
            $this->where("id = %d", $userId)->save(['pmsg' => $_msg]);
        }
        return $data;
    }

    public function addBalance($userId, $price)
    {
        $info = $this->where("id = %d", $userId)->find();
        if (!$info) {
            return false;
        }
        $balance = floatval($info['balance']);
        $balance += floatval($price);
        if ($balance < 0) {
            return false;
        }
        $save = $this->where("id = %d", $userId)->save(['balance' => $balance]);
        return $save;
    }

    public function getUserInfo($userId)
    {
        $info = $this->where("id = %d", $userId)
            ->field('id_number, realname, tel, qq_num, wx_id, nickname, avatar, addr, sign')->find();
        return $info;
    }

    public function getUserPermission($userId)
    {
        $info = $this->where("id = %d", $userId)->find();
        if (!$info)
            return 0;
        else
            return $info['perm'];
    }


}