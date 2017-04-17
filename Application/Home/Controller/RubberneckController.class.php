<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/4/16
 * Time: 17:02
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\RubberneckModel;


class RubberneckController extends BaseController
{

    public function createTopic()
    {
        $userId = $this->reqLogin();

        $data = $this->reqPost(array('title', 'content'));

        $data['author_id'] = $userId;
        $data['ctime'] = time();
        $data['has_comm'] = 0;

        $model = new RubberneckModel();
        $create = $model->createTopic($data);

        if ($create) {
            $data['id'] = $create;
            $this->ajaxReturn(qc_json_success($data));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to create topic'));
        }
    }

    public function deleteTopic()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.del_id');

        $model = new RubberneckModel();
        $delFlag = $model->deleteTopic($del_id, $userId);
        if ($delFlag)
            $this->ajaxReturn(qc_json_success('Delete success'));
        else
            $this->ajaxReturn(qc_json_error('Delete failed'));
    }

    public function editTopic()
    {
        $userId = $this->reqLogin();
        $postData = $this->reqPost(array('tp_id'), array('title', 'content'));
        $topic_id = $postData['tp_id'];
        unset($postData['tp_id']);

        if (count($postData, COUNT_NORMAL) == 0) {
            $this->ajaxReturn(qc_json_error('No extra param'));
        }

        $model = new RubberneckModel();
        $save = $model->where("id = %d and author_id = %d", $topic_id, $userId)->save($postData);
        if ($save)
            $this->ajaxReturn(qc_json_success('Update success'));
        else
            $this->ajaxReturn(qc_json_error('Update failed'));
    }

    public function getMyTopicList()
    {
        $userId = $this->reqLogin();
        $model = new RubberneckModel();
        $data = $model->where("author_id = $userId")->select();
        $this->ajaxReturn(qc_json_success($data));
    }

    public function getUserTopicList()
    {
        $userId = I('post.user_id');
        if (!$userId) {
            $this->ajaxReturn(qc_json_error('Need param: user_id'));
        }
        $model = new RubberneckModel();
        $data = $model->where("author_id = %d", $userId)->select();
        $this->ajaxReturn(qc_json_success($data));
    }


    public function getRecentTopicList()
    {
        $model = new RubberneckModel();
        $list = $model->recentTopicList();
        $this->ajaxReturn(qc_json_success($list));
    }


}