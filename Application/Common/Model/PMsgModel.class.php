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

    public function sendPrivateMessage($info)
    {

    }

    //status of private message -1: receiver delete, 1: sender delete
    public function deletePrivateMessage($del_id, $user_id)
    {
        $data = $this->where("id = %d", $del_id);
        if ($data)
            return false;
        $status = $data['status'];
        switch ($status) {
            case -1:
                if ($user_id == $data['sender'])
                    return $this->delete($del_id);
                break;

            case 0:
                if ($user_id == $data['sender'])
                    return $this->save(['status' => 1]);
                else if ($user_id == $data['receiver'])
                    return $this->save(['status' => -1]);
                break;

            case 1:
                if ($user_id == $data['receiver'])
                    return $this->delete($del_id);
                break;

            default:
                break;
        }
        return false;
    }

    public function getPrivateMessageList($userId, $msgType = 'send')
    {

        switch ($msgType) {
            case 'send':
                $list = $this->where("sender = %d and type = 0 and status != 1", $userId)->select();
                break;

            case 'receive':
                $userModel = new UserModel();
                $userModel->clearUserNotice($userId);
                $list = $this->where("receiver = %d and type = 0 and status != -1", $userId)->select();
                break;

            default:
                return false;
        }
        return $list;
    }
}