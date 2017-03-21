<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/20
 * Time: 22:31
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\TransactModel;
use Common\Model\TrCommentModel;

class TrCommentController extends BaseController
{
    public function leaveComment()
    {
        $userId = $this->reqLogin();
        $postData = $this->reqPost('tid', 'content');
        $traId = $postData['tid'];
        $model = new TransactModel();
        if (!$model->where("id = %d", $traId)->find()) {
            $this->ajaxReturn(qc_json_error('Transaction id does not exist'));
        }
        $comm = array(
            'tr_id' => $traId,
            'user_id' => $userId,
            'content' => $postData['content'],
            'ctime' => time()
        );
        $model = new TrCommentModel();
        $add = $model->add($comm);
        if ($add) {
            $comm['id'] = $add;
            $this->ajaxReturn(qc_json_success($comm));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to create comment'));
        }
    }

    public function deleteComment()
    {
        $userId = $this->reqLogin();
        $delId = I('post.del_id');
        $model = new TrCommentModel();
        $delete = $model->where("user_id = %d and tr_id = %d", $userId, $delId)->delete();
        if ($delete)
            $this->ajaxReturn(qc_json_success('Delete success'));
        else
            $this->ajaxReturn(qc_json_error('Delete failed'));
    }


    public function giveLikeToComment()
    {
        $userId = $this->reqLogin();
        $commId = I('post.comm_id');
        $model = new TrCommentModel();
        $likec = $model->giveLikeToComment($userId, $commId);
        if (-1 == $likec)
            $this->ajaxReturn(qc_json_error('Failed to give a like'));
        else
            $this->ajaxReturn(qc_json_success($likec));
    }

    public function getCommentList()
    {
        $t_id = I('post.t_id');
        $model = new TrCommentModel();
        $commList = $model->where("tr_id = %d", $t_id)->select();
        $count = count($commList, COUNT_NORMAL);
        $this->ajaxReturn(qc_json_success(['count' => $count, 'comm_list' => $commList]));
    }

//    public function test()
//    {
//        var_dump((new TrCommentModel())->getDbFields());
//    }
}