<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/28
 * Time: 22:37
 */

namespace Common\Model;


class ReqCommentModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('reqcomm', $this->tablePrefix, $this->connection);
    }

    public function leaveComment($info)
    {
        $add = $this->add($info);
        if ($add) {
            $reqId = $info['req_id'];
            $model = new RequirementModel();
            $model->where("id = $reqId")->save(['has_comm' => 1]);
        }
        return $add;
    }

    public function deleteComment($userId, $del_id)
    {
        $deleteFlag = $this->where("id = %d", $del_id)->delete();
        return $deleteFlag;
    }

    public function giveLikeToComment($userId, $commId)
    {
        $data = $this->where("id = %d ", $commId)->find();
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