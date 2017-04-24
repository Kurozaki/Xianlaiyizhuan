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

    public function recentRequirementList()
    {
        $recent = F('recent_req');
        if (!$recent) {
            return null;
        }

        $condition = ['id' => ['in', $recent]];
        $data = $this->where($condition)->select();

        $userModel = new UserModel();
        foreach ($data as &$info) {
            if ($info['pics']) {
                $info['pics'] = explode("|", $info['pics']);
                foreach ($info['pics'] as &$url) {
                    $url = C('BASE_URL') . $url;
                }
            } else {
                $info['pics'] = null;
            }
            $reqUser = $info['req_user'];
            $userInfo = $userModel->userBaseInfo($reqUser);
            $info['req_user'] = $userInfo;
        }

        return $data;
    }

    public function requirementList($offset, $length)
    {
        $data = $this->limit($offset, $length)->select();
        if (!$data)
            return null;

        $userModel = new UserModel();
        foreach ($data as &$info) {
            $userInfo = $info['req_user'];
            $userInfo = $userModel->userBaseInfo($userInfo);
            $info['req_user'] = $userInfo;


            if ($info['pics']) {
                $info['pics'] = explode("|", $info['pics']);
                foreach ($info['pics'] as &$url) {
                    $url = C('BASE_URL') . $url;
                }
            } else {
                $info['pics'] = null;
            }
        }

        return $data;
    }
}