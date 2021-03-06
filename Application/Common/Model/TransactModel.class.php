<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/17
 * Time: 21:55
 */

namespace Common\Model;


class TransactModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('transact', $this->tablePrefix, $this->connection);
    }

    public function giveLike($userId, $tra_id)
    {
        $find = $this->where("id = %d", $tra_id)->find();
        if ($find) {
            $give = $this->giveLikeToPost($userId, $tra_id, C('COMMENT_TYPE_TRANSACT'));
            if ($give) {
                $likec = $find['likec'] + 1;

                $save = $this->where("id = %d", $tra_id)->save(['likec' => $likec]);

                return $save ? $likec : false;
            } else
                return $give;
        }
        return false;
    }


    public function createTransact($info)
    {
        if ($info['free'] == 1) {
            $info['price'] = 0;
        }
        $addId = $this->add($info);
        if ($addId) {
            $recent = F('recent_tra');
            if (!$recent)
                $recent = array();
            array_unshift($recent, $addId);
            if (count($recent, COUNT_NORMAL) > 20) {
                array_pop($recent);
            }
            F('recent_tra', $recent);
        }
        return $addId;
    }

    public function deleteTransact($delId, $userId)
    {
        //delete record from database
        $deleteFlag = $this->where("id = %d and seller_id = %d", $delId, $userId)->delete();
        if ($deleteFlag) {      //if delete success, remove the record in cache
            $recent = F('recent_tra');
            if (is_array($recent))
                array_del_by_val($recent, $delId);
            F('recent_tra', $recent);
        }
        return $deleteFlag;
    }

    public function recentTransactList($type = null)
    {
        $recent = F('recent_tra');
        if (!$recent) {
            return null;
        }

        $condition = ['id' => ['in', $recent]];
        if ($type)
            $condition['type'] = $type;

        $data = $this->where($condition)->select();

        $userModel = new UserModel();
        foreach ($data as &$info) {

            //get user base info
            $sellerId = $info['seller_id'];
            $userInfo = $userModel->userBaseInfo($sellerId);
            $info['seller'] = $userInfo;
            unset($info['seller_id']);

            //change pic to array
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

    public function getUserTransactList($userId, $free)
    {
        switch ($free) {
            case 'true':
                $data = $this->where(array('seller_id' => $userId, 'free' => 1))->select();
                break;

            case 'false':
                $data = $this->where(array('seller_id' => $userId, 'free' => 0))->select();
                break;

            default:
                $data = $this->where(array('seller_id' => $userId))->select();
                break;
        }
        return $data;
    }

    public function transactionList($offset, $length, $type)
    {
        if ($type == -1) {
            $data = $this->order("id desc")->limit($offset, $length)->select();
        } else {
            $data = $this->where("type = %d", $type)->order("id desc")
                ->limit($offset, $length)->select();
        }

        if (!$data) {
            return null;
        }

        $userModel = new UserModel();
        foreach ($data as &$info) {
            if ($info['pics']) {
                $info['pics'] = explode("|", $info['pics']);
                foreach ($info['pics'] as &$url) {
                    $url = C('BASE_URL') . $url;
                }
            } else
                $info['pics'] = null;

            $seller = $info['seller_id'];
            $userInfo = $userModel->userBaseInfo($seller);
            $info['seller'] = $userInfo;
            unset($info['seller_id']);
        }
        return $data;
    }

    /**
     * @param $tranId
     * @param $userId
     * @param $_operate
     * @param array $new_path_list
     * @return int  -1: could not find transaction info, -2: illegal param
     */

    public function editTransactPictures($tranId, $userId, $_operate, $new_path_list = array())
    {
        $_info = $this->where("id = %d and seller_id = %d", $tranId, $userId)->find();
        if (!$_info) {
            return -1;
        }

        $opt_pattern = "/^([du][0-9]\\-){0,7}([du][0-9])$/";
        $flag = preg_match($opt_pattern, $_operate);
        if ($flag == 0) {
            return -2;
        }

        $pics = $_info['pics'];

        $pic_arr = explode('|', $pics);
        $op_arr = explode("-", $_operate);

        foreach ($op_arr as $op_step) {
            $temp = split_str($op_step, 1);
            $op_type = $temp[0];
            $op_index = $temp[1];

            if ($op_type == 'd') {
                if (isset($pic_arr[$op_index])) {
                    unlink("./" . $pic_arr[$op_index]);         //delete file
                    unset($pic_arr[$op_index]);
                }
            } elseif ($op_type == 'u') {
                if (count($new_path_list, COUNT_NORMAL) > 0) {
                    if (isset($pic_arr[$op_index])) {
                        $pic_arr[$op_index] = array_shift($new_path_list);
                    } else {
                        array_push($pic_arr, array_shift($new_path_list));
                    }
                }
            }
        }

        $newPics = "";
        foreach ($pic_arr as $val) {
            $newPics .= ($val . "|");
        }
        $newPics = substr($newPics, 0, strlen($newPics) - 1);
        $save = $this->where("id = %d and seller_id = %d", $tranId, $userId)->save(['pics' => $newPics]);

        return $save;
    }
}