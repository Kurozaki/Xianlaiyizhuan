<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/9
 * Time: 19:42
 */

namespace Home\Controller;


use Common\Model\TransactModel;
use Think\Upload;

class TransactController extends BaseController
{
    public function create_transact_info()
    {
        $this->req_user_login();

        $userId = session('user_id');

        $cFields = array('goods_name', 'goods_intro', 'price', 'type');
        $tInfo = array('seller_id' => $userId);

        //get post params
        foreach ($cFields as $key) {
            $val = I('post.' . $key);
            if (is_invalid_param($val)) {
                $this->ajaxReturn(qc_json_error('Need param: ' . $key));
            }
            $tInfo[$key] = $val;
        }

        $tInfo['ctime'] = time();

        //get upload pics
        $saveRoot = C('AVATAR_STORE_ROOT');
        $saveRoot = $saveRoot . 'UserAvatar/';
        $uploadFile = new Upload(array('rootPath' => $saveRoot));
        $info = $uploadFile->upload();
        $counter = 0;

        $imgsPath = '';
        foreach ($info as $img) {
            $path = '[' . $saveRoot . $img['savepath'] . $img['savename'] . ']';
            $imgsPath .= $path;
            if (++$counter > 3) {
                break;
            }
        }
        $tInfo['pics'] = $imgsPath;

        $model = new TransactModel();
        $create = $model->add($tInfo);
        if ($create) {
            $this->ajaxReturn(qc_json_success('Create success!'));
        } else {
            $this->ajaxReturn(qc_json_error('Create failed!', 40002));
        }
    }

    public function modify_transact_info()
    {
        $this->req_user_login();
        $userId = session('user_id');
        $traId = I('t_id');

        $mFields = array('goods_name', 'goods_intro', 'type', 'price');
        $mData = null;

        foreach ($mFields as $key) {
            $val = I('post.' . $key);
            if (!is_invalid_param($val)) {
                $mData[$key] = $val;
                if ('price' == $key && floatval($val) < 0) {
                    $this->ajaxReturn(qc_json_error('Illegal param!'));
                }
            }
        }
        if (is_null($mData)) {
            $this->ajaxReturn('No new info.', 40002);
        }

        $traModel = new TransactModel();
        $save = $traModel->where("id = %d and seller_id = $userId", $traId)->save($mData);
        $save ? $this->ajaxReturn(qc_json_success('Update success.')) : $this->ajaxReturn(qc_json_error('Failed to
        update.', 40002));
    }

    public function delete_transact_info()
    {
        $this->req_user_login();

        $del_id = intval(I('del_id'));
        $userId = session('user_id');
        $trModel = new TransactModel();
        $delRes = $trModel->where("id = $del_id and seller_id = $userId")->delete();
        $delRes ? $this->ajaxReturn(qc_json_success('Delete success')) : $this->ajaxReturn(qc_json_error('Deleted
        transact does not exist'));
    }

    public function get_transact_list()
    {
        $this->req_user_login();

        $queryCount = 15;
//        $queryCount = I('post.query_count');
        $start = intval(I('offset'));
        $userId = session('user_id');

        $trModel = new TransactModel();
        $data = $trModel->where("seller_id = $userId")->limit($start, $queryCount)->select();
        $this->ajaxReturn(qc_json_success('Success', array('end' => $start + $queryCount, 'list' => $data)));
    }

    public function give_transact_like()
    {
        $this->req_user_login();

        $userId = session('user_id');
        $trId = intval(I('post.t_id'));

        $likec = intval(I('post.likec')) > 0 ? 1 : -1;
        $trModel = new TransactModel();
        $res = $trModel->where("id = $trId and seller_id = $userId")->save(array('likec' => $likec));
        $res ? $this->ajaxReturn(qc_json_success('Operate success')) : $this->ajaxReturn('Error', 40002);
    }
}