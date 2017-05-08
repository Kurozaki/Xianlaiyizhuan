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

        $data = $this->reqPost(array('content', 'picstr'));

        $data['author_id'] = $userId;
        $data['ctime'] = time();
        $data['has_comm'] = 0;
        $data['likec'] = 0;

        $picStr = $data['picstr'];
        $pic_arr = explode(",", $picStr);
        $rootPath = C('FILE_STORE_ROOT') . "rubberneck/rubberneck_info/";
        $picsPath = "";
        unset($data['picstr']);
        foreach ($pic_arr as $base64) {
            $savePath = $rootPath . md5(rand()) . ".jpg";
            $this->base64FileDecode($base64, $savePath);
            $picsPath .= (substr($savePath, 2) . "|");
        }
        $data['pics'] = substr($picsPath, 0, strlen($picsPath) - 1);

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
        $postData = $this->reqPost(array('tp_id'), array('content'));
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

        if (!$data) {
            $this->ajaxReturn(qc_json_null_data());
        }

        if (is_array($data)) {
            foreach ($data as &$info) {
                $info['pics'] = explode("|", $info['pics']);
            }
        }
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

        if (!$data) {
            $this->ajaxReturn(qc_json_null_data());
        }

        if (is_array($data)) {
            foreach ($data as &$info) {
                $info['pics'] = explode("|", $info['pics']);
            }
        }
        $this->ajaxReturn(qc_json_success($data));
    }

    public function getAllTopicList()
    {
        $offset = I('post.offset');
        if (!$offset)
            $offset = 0;
        else
            $offset = intval($offset);

        $model = new RubberneckModel();
        $data = $model->topicList($offset, C('COUNT_PAGING'));

        if ($data)
            $this->ajaxReturn(qc_json_success(array(
                'offset' => $offset + count($data, COUNT_NORMAL),
                'data' => $data
            )));
        else
            $this->ajaxReturn(qc_json_null_data());
    }

    public function giveLikeToTopic()
    {
        $userId = $this->reqLogin();
        $tp_id = I('post.tp_id');

        if (!$tp_id) {
            $this->ajaxReturn(qc_json_error('Need param: tp_id'));
        }

        $model = new RubberneckModel();
        $likec = $model->giveLike($userId, $tp_id);

        if ($likec) {
            $this->ajaxReturn(qc_json_success(['likec' => $likec]));
        } else {
            $this->ajaxReturn(qc_json_error('Failed'));
        }
    }


}