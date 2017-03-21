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

        $tInfo = $this->reqPost(array('intro', 'type', 'price'));

        //set seller id and create time
        $tInfo['seller_id'] = $userId;
        $tInfo['ctime'] = time();
        $tInfo['sell'] = 0;

        $fPaths = '';
        $fInfo = $this->uploadPictures('transact_intro', true);
        $fArr = $fInfo['success_array'];

        if (count($fArr, COUNT_NORMAL) == 0)
            $this->ajaxReturn(qc_json_error_request('Request at least one picture'));

        foreach ($fArr as $f) {
            $fPaths .= $f['url'] . '|';     //connect pic urls with '|'
        }
        $fPaths = substr($fPaths, 0, strlen($fPaths) - 1);
        $tInfo['pics'] = $fPaths;

        $model = new TransactModel();
        $add = $model->add($tInfo);
        if ($add) {
            $tInfo['id'] = $add;
            $this->put_recent_transact_id($add);    //put this record to cache after creation
            $this->ajaxReturn(qc_json_success($tInfo));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to create.'));
        }
    }

    public function deleteTransaction()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.del_id', 0);
        if (0 == $del_id) {
            $this->ajaxReturn(qc_json_error('Please post the delete id'));
        }

        $model = new TransactModel();
        $delete = $model->where("id = %d and seller_id = %d", $del_id, $userId)->delete();

        $delete ? $this->ajaxReturn(qc_json_success('Delete success')) :
            $this->ajaxReturn(qc_json_error('You have no permission to delete it'));
    }

    public function updateTransactionInfo()
    {
        $userId = $this->reqLogin();
        $update_id = I('post.update_id');
        $data = $this->reqPost(null, array('intro', 'type', 'price'));
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
        $model = new TransactModel();
        $data = $model->where("seller_id = %d", $userId)->select();
        $this->ajaxReturn(qc_json_success($data));
    }

    public function specifyUserTransactionList()
    {
        $seller_id = I('post.seller_id');
        $model = new TransactModel();
        $data = $model->where("seller_id = %d", $seller_id)->select();
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
        $recent = $this->get_recent_transact_id();
        if (!$recent || count($recent) == 0) {
            $this->ajaxReturn(qc_json_success(null));
        }
        $model = new TransactModel();
        $data = $model->where(['id' => ['in', $recent]])->select();
        $this->ajaxReturn(qc_json_success($data));
    }

    private function put_recent_transact_id($id)
    {
        $recent = F('recent_tra');
        if (!$recent) {
            $recent = array();
        }
        array_push($recent, $id);
        if (count($recent, COUNT_NORMAL) > 20) {    //allow 20 recent records
            array_shift($recent);
        }
        F('recent_tra', $recent);
    }

    private function get_recent_transact_id()
    {
        $recent = &F('recent_tra');
        return $recent ? $recent : [];
    }

    public function test()
    {
//        $res = $this->uploadPictures('transact_intro', true);
//        var_dump($res);
        echo 'Your are my son?  ';
    }
}