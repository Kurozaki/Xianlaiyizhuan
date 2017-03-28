<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/17
 * Time: 21:57
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\TransactModel;

class TransactController extends BaseController
{
    public function createTransaction()
    {
        $userId = $this->reqLogin();

        $tInfo = $this->reqPost(array('intro', 'type', 'price', 'free'));

        //set seller id and create time
        $tInfo['seller_id'] = $userId;
        $tInfo['ctime'] = time();
        $tInfo['sell'] = 0;

        $fPaths = '';
        $fInfo = $this->uploadPictures('transact_intro', true);
        $fArr = $fInfo['success_array'];

        if (!is_array($fArr) || count($fArr, COUNT_NORMAL) == 0)
            $this->ajaxReturn(qc_json_error_request('Request at least one picture'));

        foreach ($fArr as $f) {
            $fPaths .= $f['url'] . '|';     //connect pic urls with '|'
        }
        $fPaths = substr($fPaths, 0, strlen($fPaths) - 1);
        $tInfo['pics'] = $fPaths;

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
        $postData = $this->reqPost(array('update_id', 'op_str'));
        $fInfo = $this->uploadPictures('transact_intro', true);
        $fArr = $fInfo['success_array'];

        $fPaths = array();
        foreach ($fArr as $f) {
            array_push($fPaths, $f['url']);
        }
        $model = new TransactModel();
        $update = $model->editTransactPics($userId, $postData['update_id'], $postData['op_str'], $fPaths);

        if ($update) {
            $this->ajaxReturn(qc_json_success($update));
        } else
            $this->ajaxReturn(qc_json_error('Failed to update'));
    }

    public function getMyTransactionList()
    {
        $userId = $this->reqLogin();
        $freeFlag = I('post.free');
        $model = new TransactModel();
        $data = $model->getUseerTransactList($userId, $freeFlag);
        $this->ajaxReturn(qc_json_success($data));
    }

    public function specifyUserTransactionList()
    {
        $seller_id = I('post.seller_id');
        $freeFlag = I('post.free');
        $model = new TransactModel();
        $data = $model->getUseerTransactList($seller_id, $freeFlag);
        $this->ajaxReturn(qc_json_success($data));
    }

    public function giveLikeToTransaction()
    {
        $this->reqLogin();
        $like_tId = I('post.tid');

        $model = new TransactModel();
        $giveLike = $model->giveLike($like_tId);

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


//    public function test()
//    {
//        $res = $this->uploadPictures('transact_intro', true);
//        var_dump($res);
//        echo 'Your are my son?  ';
//    }
}