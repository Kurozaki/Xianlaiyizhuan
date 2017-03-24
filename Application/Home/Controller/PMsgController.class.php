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
        $data = $this->reqPost(array('receiver', 'content'));

        $receiver = $data['receiver'];
        if ($receiver == $userId) {
            $this->ajaxReturn(qc_json_error('Could not send message to yourself.'));
        }

        $userModel = new UserModel();
        $find = $userModel->where("id = %d", $receiver)->find();
        if ($find) {

            $data['sender'] = $userId;
            $data['ctime'] = time();
            $data['status'] = 0;
            $data['type'] = 0;
            $data['mark'] = 0;

            $model = new PMsgModel();
            $add = $model->sendPrivateMessage($data);
            if ($add) {
                $data['id'] = $add;
                $this->ajaxReturn(qc_json_success($data));
            } else
                $this->ajaxReturn(qc_json_error('Failed to send private message'));
        } else {
            $this->ajaxReturn(qc_json_error('The receiver user does not exist'));
        }
    }

    public function deletePMsg()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.pm_id');

        $model = new PMsgModel();
        $delete = $model->deletePrivateMessage($del_id, $userId);

        if ($delete) {
            (new UserModel())->clearUserNotice($userId);
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else
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
            $this->ajaxReturn(qc_json_success($list));
        }
    }

    public function getPMsgNotice()
    {
        $userId = $this->reqLogin();
        $pmsg = S('pmsg_uid_' . $userId);
        if ($pmsg) {
            //if the pm notice has exist in the cache, return it

            $this->ajaxReturn(qc_json_success(['pm' => $pmsg]));
        } else {
            //if pm notice don't exist in the cache, search in the db and put it into cache

            $model = new UserModel();
            $res = $model->where('id = %d', $userId)->field('pmsg')->find();
            $pmsg = $res['pmsg'];
            S('pmsg_uid_' . $userId, $pmsg, 7200);
            $res ? $this->ajaxReturn(qc_json_success(['pm' => $pmsg])) :
                $this->ajaxReturn(qc_json_error('Get notice error'));
        }
    }

}