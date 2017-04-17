<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/30
 * Time: 8:49
 */

namespace Common\Model;


class CommentModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('comment', $this->tablePrefix, $this->connection);
    }

    public function leaveComment($commInfo)
    {
        $p_id = $commInfo['p_id'];
        $type = $commInfo['type'];

        switch ($type) {
            case C('COMMENT_TYPE_TRANSACT'):
                $model = new TransactModel();
                break;

            case C('COMMENT_TYPE_REQ'):
                $model = new RequirementModel();
                break;

            case C('COMMENT_TYPE_DONATION'):
                $model = new DonationModel();
                break;

            case C('COMMENT_TYPE_RUBBERNECK'):
                $model = new RubberneckModel();
                break;

            default:
                return false;
        }

        $find = $model->where("id = %d", $p_id)->find();    //check if the post id exist first
        if ($find) {
            $model->where("id = %d", $p_id)->save(['has_comm' => (intval($find['has_comm']) + 1)]);
            $add = $this->add($commInfo);   //if post found, add this comment
            return $add;
        } else {
            return false;   //if post not found, return false
        }
    }

    public function deleteComment($user_id, $comm_id)
    {
//        $delFlag = $this->where("id = %d and user_id = %d", $comm_id, $user_id)->delete();
//        return $delFlag;
        $info = $this->where("id = %d and user_id = %d", $comm_id, $user_id)->find();
        if (!$info) {
            return false;
        }
        switch ($info['type']) {
            case C('COMMENT_TYPE_TRANSACT'):
                $model = new TransactModel();
                break;

            case C('COMMENT_TYPE_REQ'):
                $model = new RequirementModel();
                break;

            case C('COMMENT_TYPE_DONATION'):
                $model = new DonationModel();
                break;

            case C('COMMENT_TYPE_RUBBERNECK'):
                $model = new RubberneckModel();
                break;

            default:
                return false;
        }
        $p_id = $info['p_id'];

        $find = $model->where("id = $p_id")->find();
        $model->where("id = $p_id")->save(['has_comm' => (intval($find['has_comm']) - 1)]);

        $delFlag = $this->where("id = %d and user_id = %d", $comm_id, $user_id)->delete();
        return $delFlag;
    }

    public function userCommentList($user_id, $comment_type)
    {
        $data = $this->where("user_id = %d", $user_id)->select();
        return $data;
    }

    public function infoCommentList($type, $p_id)
    {
        $data = $this->where("p_id = %d and type = %d", $p_id, $type)->select();
        return $data;
    }

}