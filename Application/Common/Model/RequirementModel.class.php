<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/22
 * Time: 21:42
 */

namespace Common\Model;


class RequirementModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('requirement', $this->tablePrefix, $this->connection);
    }

    public function createReq($data)
    {
        $add = $this->add($data);
        if ($add) {
            $recentReq = F('recent_req');
            if (!$recentReq) {
                $recentReq = array();
            }
            array_unshift($recentReq, $add);
            if (count($recentReq, COUNT_NORMAL) > 20)   //max 20 recent records
                array_pop($recentReq);
            F('recent_req', $recentReq);
        }
        return $add;
    }

    public function deleteReq($reqId, $userId)
    {
        $delete = $this->where("id = %d and req_user = %d", $reqId, $userId)->delete();
        if ($delete) {
            $recent = F('recent_req');
            if (is_array($recent)) {
                $flag = array_del_by_val($recent, $reqId);
                if ($flag)
                    F('recent_req', $recent);
            }
        }
        return $delete;
    }
}