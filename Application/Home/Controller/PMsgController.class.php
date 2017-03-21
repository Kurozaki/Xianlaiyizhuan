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
        $data = $this->reqPost('receiver', 'content');

        if ($data['receiver'] == $userId) {
            $this->ajaxReturn(qc_json_error('Could not send message to yourself.'));
        }
        $data['sender'] = $userId;
        $data['ctime'] = time();
        $data['status'] = 0;
        $data['type'] = 0;

        $model = new PMsgModel();
        $add = $model->add($data);
        if ($add) {
            $data['id'] = $add;
            $this->ajaxReturn(qc_json_success($data));
        } else
            $this->ajaxReturn(qc_json_error('Failed to send private message'));
    }

    public function deletePMsg()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.pm_id');
        $model = new PMsgModel();
        $delete = $model->deletePrivateMessage($del_id, $userId);
        if ($delete)
            $this->ajaxReturn(qc_json_success('Delete success'));
        else
            $this->ajaxReturn(qc_json_error('Failed to delete'));
    }

    public function getPMsgList()
    {
        $userId = $this->reqLogin();
        $msgType = I('post.m_type');
        $model = new PMsgModel();
        $list = $model->getPrivateMessageList($userId, $msgType);
        if (!$list)
            $this->ajaxReturn(qc_json_error('Failed to get msg list'));
        else {
            $userModel = new UserModel();
            $userModel->clearUserNotice($userId);
            $this->ajaxReturn(qc_json_success($list));
        }
    }

    public function getPMsgNotice()
    {
        $userId = $this->reqLogin();
        $pmsg = S('pmsg_uid_' . $userId);
        if (!$pmsg) {
            $this->ajaxReturn(qc_json_success(['pm' => $pmsg]));
        } else {
            $model = new UserModel();
            $res = $model->where('id = %d', $userId)->field('pmsg')->find();
            $pmsg = $res['pmsg'];
            S('pmsg_uid_' . $userId, $pmsg, 7200);
            $res ? $this->ajaxReturn(qc_json_success(['pm' => $pmsg])) :
                $this->ajaxReturn(qc_json_error('Get notice error'));
        }
    }

//    public function test()
//    {
//        var_dump(S('sss'));
//        if (!S('sss')) {
//            S('sss', 'hahah', 200);
//        } else {
//            S('sss', null);
//        }
//
//    }
}