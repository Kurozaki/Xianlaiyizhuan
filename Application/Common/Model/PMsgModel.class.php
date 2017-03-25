<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/21
 * Time: 19:56
 */

namespace Common\Model;

//private message model

class PMsgModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('pmsg', $this->tablePrefix, $this->connection);
    }

    public function sendPM($sender, $receiver, $content, $type = 0)
    {

        $userModel = new UserModel();
        $find = $userModel->where("id = %d", $receiver)->find();
        if ($find) {

            $info = array(
                'type' => $type,
                'sender' => $sender,
                'receiver' => $receiver,
                'content' => $content,
                'ctime' => time(),
                'status' => 0,
                'mark' => 0
            );
            $add = $this->add($info);

            if ($add) {
                $info['id'] = $add;
                $userModel->addPMsgNotice($receiver);
                return $info;
            } else
                return false;
        } else
            return false;
    }

    public function getPMList($userId, $mType = 'send')
    {
        $list = [];
        switch ($mType) {
            case 'send':
                $list = $this->where("sender = %d and status != 1", $userId)->select();
                break;

            case 'receive':
                $list = $this->where("receiver = %d and status != -1", $userId)->select();
                break;

            case 'sys':
                $list = $this->where("receiver = %d and type = 1", $userId)->select();
                break;

            default:
                break;
        }
        return $list;
    }

    public function deletePM($userId, $pmId)
    {
        $flag = false;
        $_data = $this->where("id = %d", $pmId)->find();
        if (!$_data) {
            $status = intval($_data['status']);
            switch ($status) {
                case -1:
                    if ($userId == $_data['sender']) {
                        $flag = $this->where("id = %d", $pmId)->delete();
                    }
                    break;

                case 0:
                    if ($userId == $_data['sender']) {
                        $flag = $this->where("id = %d", $pmId)->save(['status' => 1]);
                    } else if ($userId == $_data['receiver']) {
                        $flag = $this->where("id = %d", $pmId)->save(['status' => -1]);
                    }
                    break;

                case 1:
                    if ($userId == $_data['receiver']) {
                        $flag = $this->where("id = %d", $pmId)->delete();
                    }
                    break;

                default:
            }
        }
        return $flag;
    }

    public function markRecPMsg($userId, $markId)
    {
        return $this->where("id = %d and receiver = %d", $markId, $userId)->save(['mark' => 1]);
    }

}