<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/30
 * Time: 8:50
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\CommentModel;

class CommentController extends BaseController
{

    public function leaveComment()
    {
        $userId = $this->reqLogin();

        $p_id = I('post.p_id');
        $type = I('post.type');
        $content = I('post.content');

        $commInfo = array(
            'p_id' => $p_id,
            'type' => $type,
            'user_id' => $userId,
            'content' => $content,
            'ctime' => time(),
            'likec' => 0
        );
        $model = new CommentModel();
        $add = $model->leaveComment($commInfo);
        if ($add) {
            $commInfo['id'] = $add;
            $this->ajaxReturn(qc_json_success($commInfo));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to leave comment'));
        }
    }

    public function deleteComment()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.del_id');

        $model = new CommentModel();
        $delFlag = $model->deleteComment($userId, $del_id);

        if ($delFlag) {
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else {
            $this->ajaxReturn(qc_json_error('Delete failed'));
        }
    }

    public function myCommentList()
    {
        $userId = $this->reqLogin();
        $type = I('post.type');

        $model = new CommentModel();
        $data = $model->userCommentList($userId, $type);

        if ($data) {
            $this->ajaxReturn(qc_json_success($data));
        } else {
            $this->ajaxReturn(qc_json_error('Operate error.'));
        }
    }

    public function postCommentList()
    {
        $pData = $this->reqPost(array('p_id', 'type'));

        $model = new CommentModel();
        $list = $model->infoCommentList($pData['type'], $pData['p_id']);

        if ($list) {
            $this->ajaxReturn(qc_json_success($list));
        } else {
            $this->ajaxReturn(qc_json_error('Operate error'));
        }
    }

}