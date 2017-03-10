<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/10
 * Time: 20:26
 */

namespace Common\Model;


class FriendModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('friendrelate', $this->tablePrefix, $this->connection);
    }

    public static function create_friend_relation($user1, $user2)
    {
        if ($user1 == $user2) {
            return false;
        }
        if ($user1 > $user2) {
            $temp = $user1;
            $user1 = $user2;
            $user2 = $temp;
        }
        $model = new self();
        $res = $model->where("uid1 = %d and uid2 = %d", $user1, $user2)->find();
        if ($res) {
            return false;
        }
        return $model->add(array('uid1' => $user1, 'uid2' => $user2));
    }

    public static function friend_list_of($user_id)
    {
        $model = new self();
        $data1 = $model->where("uid1 = $user_id")->select();
        $data2 = $model->where("uid2 = $user_id")->select();
        $friendIdList = array();
        foreach ($data1 as $val) {
            array_push($friendIdList, $val['uid2']);
        }
        foreach ($data2 as $val) {
            array_push($friendIdList, $val['uid1']);
        }
        return $friendIdList;
    }

    public static function delete_friend($user1, $user2)
    {
        if ($user1 == $user2) {
            return false;
        }
        if ($user1 > $user2) {
            $temp = $user1;
            $user1 = $user2;
            $user2 = $temp;
        }
        $model = new self();
        return $model->where("uid1 = %d and uid2 = %d", $user1, $user2)->find();
    }
}