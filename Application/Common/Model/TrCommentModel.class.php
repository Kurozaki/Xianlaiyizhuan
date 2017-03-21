<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/21
 * Time: 19:28
 */

namespace Common\Model;


class TrCommentModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('trcomm', $this->tablePrefix, $this->connection);
    }

    public function giveLikeToComment($user_id, $comm_id)
    {
        $data = $this->where("id = %d and user_id = %d", $comm_id, $user_id)->find();
        if (!$data) {
            return -1;
        }
        $likec = intval($data['likec']) + 1;
        if ($this->save(['likec' => $likec]))
            return $likec;
        else
            return -1;
    }
}