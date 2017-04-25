<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/4/16
 * Time: 20:11
 */

namespace Common\Model;


class RubberneckModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('rubberneck', $this->tablePrefix, $this->connection);
    }

    public function createTopic($data)
    {
        $add = $this->add($data);
        if ($add) {
            $recent = F('recent_rubberneck');
            if (!$recent) {
                $recent = array();
            }
            array_unshift($recent, $add);
            if (count($recent, COUNT_NORMAL) > 15) {    //max allow 15 topics
                array_pop($recent);
            }
            F('recent_rubberneck', $recent);
        }
        return $add;
    }

    public function deleteTopic($del_id, $author_id)
    {
        $delFlag = $this->where("id = %d and author_id = %d", $del_id, $author_id)->delete();
        if ($delFlag) {
            $recent = F('recent_rubberneck');
            array_del_by_val($recent, $del_id);
            F('recent_rubberneck', $recent);
        }
        return $delFlag;
    }

    public function recentTopicList()
    {
        $recent = F('recent_rubberneck');

        if (!$recent) {
            return null;
        }

        $data = $this->where(['id' => ['in', $recent]])->select();

        $userModel = new UserModel();
        foreach ($data as &$info) {
            $info['author_id'] = $userModel->userBaseInfo($info['author_id']);

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

    public function topicList($offset, $length)
    {
        $data = $this->limit($offset, $length)->select();

        if (!$data) return null;

        $userModel = new UserModel();
        foreach ($data as &$info) {
            $info['author_id'] = $userModel->userBaseInfo($info['author_id']);

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

    public function giveLike($userId, $tp_id)
    {
        $find = $this->where("id = %d", $tp_id)->find();
        if ($find) {
            $give = $this->giveLikeToPost($userId, $tp_id, C('COMMENT_TYPE_RUBBERNECK'));
            if ($give) {
                $likec = $find['likec'] + 1;

                $save = $this->where("id = %d", $tp_id)->save(['likec' => $likec]);

                return $save ? $likec : false;
            } else
                return $give;
        }
        return false;
    }

}