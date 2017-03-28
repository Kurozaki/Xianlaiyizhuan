<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/21
 * Time: 19:57
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\PMsgModel;
use Common\Model\UserModel;

class PMsgController extends BaseController
{
    public function sendPMsg()
    {
        $userId = $this->reqLogin();
        $data = $this->reqPost(array('content', 'receiver'));

        if ($userId == $data['receiver']) {
            $this->ajaxReturn(qc_json_error('Could not send message to yourself'));
        }

        $model = new PMsgModel();
        $create = $model->sendPM($userId, $data['receiver'], $data['content']);
        if ($create) {
            $this->ajaxReturn(qc_json_success($create));
        } else {
            $this->ajaxReturn(qc_json_error($model->getError()));
        }
    }

    public function deletePMsg()
    {
        $userId = $this->reqLogin();
        $del_Id = I('post.del_id');
        $model = new PMsgModel();
        $deleteFlag = $model->deletePM($userId, $del_Id);
        if ($deleteFlag) {
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else {
            $this->ajaxReturn(qc_json_error('Delete failed'));
        }
    }

    public function getPMsgList()
    {
        $userId = $this->reqLogin();
        $mType = I('post.m_type');
        $model = new PMsgModel();
        $mList = $model->getPMList($userId, $mType);
        $this->ajaxReturn(qc_json_success($mList));
    }

    public function getPMsgNotice()
    {
        $userId = $this->reqLogin();
        $_msg = S('PMsg_uid_' . $userId);
        if ($_msg)
            $this->ajaxReturn(qc_json_success($_msg));
        else {
            $userModel = new UserModel();
            $uInfo = $userModel->where("id = %d", $userId)->find();
            if ($uInfo) {
                $_msg = intval($uInfo['pmsg']);
                S('PMsg_uid_' . $userId, $_msg, 7200);
                $this->ajaxReturn(qc_json_success());
            } else
                $this->ajaxReturn(qc_json_error('Get notice failed'));
        }
    }

    //将接收的消息标记为已读
    public function markRecPMsg()
    {
        $userId = $this->reqLogin();
        $markId = I('post.mk_id');
        $model = new PMsgModel();
        $markFlag = $model->markRecPMsg($userId, $markId);
        if ($markFlag) {
            $this->ajaxReturn(qc_json_success('Mark success'));
        } else {
            $this->ajaxReturn(qc_json_error('Mark failed'));
        }
    }
}