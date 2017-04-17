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
        $data = [];
        if (is_array($recent)) {
            $data = $this->where(['id' => ['in', $recent]])->select();
        }
        return $data;
    }
}