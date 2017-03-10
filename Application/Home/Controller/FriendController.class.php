<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/10
 * Time: 19:37
 */

namespace Home\Controller;


use Common\Model\FriendModel;
use Common\Model\FriendreqModel;
use Common\Model\UserModel;

class FriendController extends BaseController
{
    public function send_friend_request()
    {
        $this->req_user_login();

        $userId = session('user_id');
        $receiver_id = I('receiver_id');
        $reqMsg = I('req_msg');

        if (!UserModel::user_exist($receiver_id)) {
            $this->ajaxReturn(qc_json_error('The user does not exist', 40002));
        }
        $reqData = array(
            'sender_id' => $userId,
            'receiver_id' => $receiver_id,
            'reqmsg' => $reqMsg,
            'time' => time()
        );
        $reqModel = new FriendreqModel();
        $add = $reqModel->add($reqData);
        $add ? $this->ajaxReturn(qc_json_success('Send request success')) : $this->ajaxReturn(qc_json_error('Failed to
        send request', 40002));
    }

    public function response_friend_request()
    {
        $this->req_user_login();

        $userId = session('user_id');

        $applyStatus = I('apply_status');
        $reqId = I('req_id');

        if (1 == $applyStatus) {

            //accept to add friend
            $reqModel = new FriendreqModel();
            $data = $reqModel->where("id = %d and receiver_id = %d", $reqId, $userId)->find();
            if (!$data) {
                $this->ajaxReturn(qc_json_error('Request does not exist', 40002));
            }
            $uId1 = $data['sender_id'];
            $uId2 = $data['receiver_id'];
            $create = FriendModel::create_friend_relation($uId1, $uId2);
            $create ? $this->ajaxReturn(qc_json_success('Add friend success')) : $this->ajaxReturn(qc_json_error('Add
            friend error', 40002));
        } elseif (2 == $applyStatus) {

            //reject to add friend
            $this->ajaxReturn(qc_json_success('Success'));
        } else {
            $this->ajaxReturn(qc_json_error('Error', 40002));
        }
    }

    public function get_friend_request_notice()
    {
        $this->req_user_login();

        $userId = session('userId');
        $reqModel = new FriendreqModel();
        $data = $reqModel->where("receiver_id = $userId")->select();
        $this->ajaxReturn(qc_json_success('Sueccess', $data));
    }

    public function my_friend_list()
    {
        $this->req_user_login();
        $userId = session('user_id');
        $friendIdList = FriendModel::friend_list_of($userId);
        $this->ajaxReturn(qc_json_success('Success', array('friend_id' => $friendIdList)));
    }

    public function delete_friend()
    {
        $this->req_user_login();

        $userId = session_id('user_id');
        $del_uId = I('post.del_uid');
        $delete = FriendModel::delete_friend($userId, $del_uId);
        $del_uId ? $this->ajaxReturn(qc_json_success('Delete success')) : $this->ajaxReturn(qc_json_error('Delete failed
        .', 40002));
    }
}