<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/28
 * Time: 17:23
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\ReqCommentModel;
use Common\Model\RequirementModel;

class ReqCommentController extends BaseController
{
    public function leaveComment()
    {
        $userId = $this->reqLogin();
        $postData = $this->reqPost(array('reqId', 'content'));
        $reqId = $postData['reqId'];
        $reqModel = new RequirementModel();

        $find = $reqModel->where("id = %d", $reqId)->find();
        if (!$find) {
            $this->ajaxReturn(qc_json_error('Failed to find this requirement'));
        }

        $comm = array(
            'req_id' => $reqId,
            'user_id' => $userId,
            'content' => $postData['content'],
            'ctime' => time(),
            'likec' => 0
        );

        $comModel = new ReqCommentModel();
        $leave = $comModel->leaveComment($comm);
        if ($leave) {

            //leave comment
            $reqModel = new RequirementModel();
            $tInfo = $reqModel->where("id = %d", $reqId)->find();
            $receiver = $tInfo['req_user'];
            $this->sendSystemMsgToUser("有人给你留言了！快去查看吧", $receiver);

            $comm['id'] = $leave;
            $this->ajaxReturn(qc_json_success($comm));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to leave comment.'));
        }
    }

    public function deleteComment()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.del_id');
        $model = new ReqCommentModel();
        $delFlag = $model->deleteComment($userId, $del_id);
        if ($delFlag) {
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else
            $this->ajaxReturn(qc_json_error('Delete failed'));
    }

    public function giveLikeToComment()
    {
        $userId = $this->reqLogin();
        $comm_id = I('post.comm_id');
        $model = new ReqCommentModel();

        $likec = $model->giveLikeToComment($userId, $comm_id);
        if (-1 == $likec) {
            $this->ajaxReturn(qc_json_error('Failed to give like'));
        } else {
            $this->ajaxReturn(qc_json_success($likec));
        }
    }

    public function getCommentList()
    {
        $req_id = I('post.reqId');
        $model = new ReqCommentModel();
        $list = $model->where("id = %d", $req_id)->select();
        $count = count($list, COUNT_NORMAL);
        $this->ajaxReturn(qc_json_success(['count' => $count, 'list' => $list]));
    }

}