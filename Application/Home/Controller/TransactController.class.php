<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/17
 * Time: 21:57
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\OrderModel;
use Common\Model\TransactModel;
use Common\Model\UserModel;

class TransactController extends BaseController
{
    public function createTransaction()
    {
        $userId = $this->reqLogin();

        $tInfo = $this->reqPost(array('intro', 'type', 'price', 'free', 'picstr'));

        //set seller id and create time
        $tInfo['seller_id'] = $userId;
        $tInfo['ctime'] = time();
        $tInfo['sell'] = 0;

        //transfer base64 to pic file
        $picStr = $tInfo['picstr'];
        $picArr = explode(",", $picStr);
        $picsPath = "";
        unset($tInfo['picstr']);
        $rootPath = C('FILE_STORE_ROOT') . "transact/transact_intro/";
        foreach ($picArr as $val) {
            $savePath = $rootPath . md5(time() * rand()) . ".jpg";
            $this->base64FileDecode($val, $savePath);
            $picsPath .= (substr($savePath, 2) . "|");
        }
        $tInfo['pics'] = substr($picsPath, 0, strlen($picsPath) - 1);

        $model = new TransactModel();
        $add = $model->createTransact($tInfo);
        if ($add) {
            $tInfo['id'] = $add;
            $this->ajaxReturn(qc_json_success($tInfo));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to create.'));
        }
    }

    public function deleteTransaction()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.del_id', 0);

        $model = new TransactModel();
        $deleteFlag = $model->deleteTransact($del_id, $userId);
        if ($deleteFlag) {
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else {
            $this->ajaxReturn(qc_json_error('You have no permission to delete it'));
        }
    }

    public function updateTransactionInfo()
    {
        $userId = $this->reqLogin();
        $update_id = I('post.update_id');
        $data = $this->reqPost(null, array('intro', 'type', 'price', 'free'));
        if (count($data, COUNT_NORMAL) == 0) {
            $this->ajaxReturn(qc_json_error('No data update'));
        }
        $model = new TransactModel();
        $update = $model->where("id = %d and seller_id = %d", $update_id, $userId);
        if ($update) {
            $this->ajaxReturn(qc_json_success('Update success'));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to update'));
        }
    }

    public function editTransactionIntroPics()
    {
        $userId = $this->reqLogin();
        $postData = $this->reqPost(array('tr_id', 'op_str', 'pics_str'));

        $picStr = $postData['pics_str'];
        $base64_arr = explode(",", $picStr);
        $pic_arr = array();

        $rootPath = C('FILE_STORE_ROOT') . "transact/transact_intro/";
        foreach ($base64_arr as $base64) {
            $savePath = $rootPath . md5(time() * rand()) . ".jpg";
            $save = $this->base64FileDecode($base64, $savePath);
            if ($save)
                array_push($pic_arr, substr($savePath, 2));
        }

        $model = new TransactModel();

        $editFlag = $model->editTransactPictures($postData['tr_id'], $userId, $postData['op_str'], $pic_arr);
        if ($editFlag > 0) {
            $this->ajaxReturn(qc_json_success('Update success'));
        } else {
            if (-1 == $editFlag) {
                $this->ajaxReturn(qc_json_error('Could not find transact info'));
            } elseif (-2 == $editFlag) {
                $this->ajaxReturn(qc_json_error('Illegal param'));
            }
        }
    }

    public function getMyTransactionList()
    {
        $userId = $this->reqLogin();
        $freeFlag = I('post.free');
        $model = new TransactModel();
        $data = $model->getUserTransactList($userId, $freeFlag);
        $this->ajaxReturn(qc_json_success($data));
    }

    public function specifyUserTransactionList()
    {
        $seller_id = I('post.seller_id');
        $freeFlag = I('post.free');
        $model = new TransactModel();
        $data = $model->getUserTransactList($seller_id, $freeFlag);
        $this->ajaxReturn(qc_json_success($data));
    }


    public function giveLikeToTransaction()
    {
        $userId = $this->reqLogin();
        $like_tId = I('post.tid');

        $model = new TransactModel();
        $giveLike = $model->giveLike($userId, $like_tId);

        if ($giveLike != -1) {
            $this->ajaxReturn(qc_json_success(['likec' => $giveLike]));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to give like'));
        }
    }

    public function getRecentTransactionList()
    {
        $model = new TransactModel();
        $dataList = $model->recentTransactList();
        $this->ajaxReturn(qc_json_success($dataList));
    }

    public function setToSoldStatus()
    {
        $userId = $this->reqLogin();
        $t_id = I('post.t_id');
        $model = new TransactModel();
        $flag = $model->where("id = %d and seller_id = %d", $t_id, $userId)->save(['sell' => 1]);
        if ($flag) {
            $this->ajaxReturn(qc_json_success('Operate success'));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to operate'));
        }
    }

    //创建订单
    public function createOrder()
    {
        $userId = $this->reqLogin();
        $data = $this->reqPost(array('pay_pwd', 'tr_id'));
        $payPwd = $data['pay_pwd'];
        $tr_id = $data['tr_id'];

        $userModel = new UserModel();   //confirm pay password
        $payConfirm = $userModel->payPasswordConfirm($userId, $payPwd);
        if (!$payConfirm) {
            $this->ajaxReturn(qc_json_error('Error pay password!'));
        }

        $tModel = new TransactModel();
        $tInfo = $tModel->where("id = %d", $tr_id)->find();
        if (!$tInfo) {
            $this->ajaxReturn(qc_json_error('Transaction info does not exist'));
        }

        $seller = $tInfo['seller_id'];
        $price = $tInfo['price'];

        if ($seller == $userId) {
            $this->ajaxReturn(qc_json_error('Could not buy something you sell.'));
        }

        $orderData = array(
            't_id' => $tr_id,
            'buyer' => $userId,
            'seller' => $seller,
            'price' => $price,
            'status' => 0
        );

        $model = new OrderModel();
        $create = $model->createOrderInfo($orderData);
        if ($create > 0) {
            $content = "有人买了你的东西！";
            $this->sendSystemMsgToUser($content, $seller);
            $this->ajaxReturn(qc_json_error('Create order success'));
        } else {
            if ($create == -1) {
                $this->ajaxReturn(qc_json_error('Low balance'));
            } else if ($create == -2) {
                $this->ajaxReturn(qc_json_error('The order has exist'));
            } else
                $this->ajaxReturn(qc_json_error('Failed to create order.'));
        }
    }

    public function finishOrder()
    {
        $userId = $this->reqLogin();
        $orderId = I('post.order_id');

        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));
        $oModel = new OrderModel();
        $save = $oModel->where("id = %d", $orderId)->save(['perm' => 1]);
        if ($save) {
            $this->ajaxReturn(qc_json_success('Operate success'));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to operate'));
        }
    }

    public function getOrderList()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));
        $oModel = new OrderModel();
        $data = $oModel->select();
        $this->ajaxReturn(qc_json_success($data));
    }


}