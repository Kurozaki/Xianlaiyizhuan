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

    public function regUserInfo($userInfo)
    {
        $userInfo['password'] = md5($userInfo['password']);
        return $this->add($userInfo);
    }

    public function addUserPMNotice($userId)
    {
        $info = $this->where("id = %d", $userId)->find();
        $notice = $info['pmsg'] + 1;
        return $this->save(['pmsg' => $notice]);
    }

    public function clearUserNotice($userId)
    {
        return $this->where("id = %d", $userId)->save(['pmsg' => 0]);
    }
}