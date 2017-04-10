<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/4/6
 * Time: 13:15
 */

namespace Common\Model;


class GiveLikeModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('givelike', $this->tablePrefix, $this->connection);
    }

    public function giveLike($user_id, $p_id, $type)
    {
        $condition = sprintf("user_id = %d and p_id = %d and type = %d", $user_id, $p_id, $type);
        $find = $this->where($condition)->find();
        if ($find) {
            return false;
        } else {
            $data = array(
                'user_id' => $user_id,
                'p_id' => $p_id,
                'type' => $type
            );
            $add = $this->add($data);
            return $add;
        }
    }

    public function cancelLike($user_id, $p_id, $type)
    {
        $condition = sprintf("user_id = %d and p_id = %d and type = %d", $user_id, $p_id, $type);
        $delFlag = $this->where($condition)->delete();
        return $delFlag;
    }
}