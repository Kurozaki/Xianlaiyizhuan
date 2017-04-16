<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/30
 * Time: 10:03
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\DonationModel;

class DonationController extends BaseController
{

    public function createDonationInfo()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));

        $postData = $this->reqPost(array('intro', 'publisher', 'addr'));

        $postData['ctime'] = time();
        $postData['has_comm'] = 0;
        $postData['likec'] = 0;

        $dModel = new DonationModel();
        $create = $dModel->createDonation($postData);
        if ($create) {
            $postData['id'] = $create;
            $this->ajaxReturn(qc_json_success($postData));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to create'));
        }
    }

    public function updateDonationInfo()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));
        $postData = $this->reqPost(array('dn_id'), array('intro', 'publisher', 'addr'));

        $dnId = $postData['dn_id'];
        unset($postData['dn_id']);

        if (count($postData, COUNT_NORMAL) == 0) {
            $this->ajaxReturn(qc_json_error('Require at least one param'));
        }

        $dModel = new DonationModel();
        $save = $dModel->where("id = %d", $dnId)->save($postData);
        if ($save) {
            $this->ajaxReturn(qc_json_success('Update success'));
        } else {
            $this->ajaxReturn(qc_json_error('Update failed'));
        }
    }

    public function deleteDonationInfo()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));
        $delId = I('post.del_id');

        $model = new DonationModel();
        $delFlag = $model->where("id =%d", $delId)->delete();
        if ($delFlag) {
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else {
            $this->ajaxReturn(qc_json_error('Delete failed'));
        }
    }

    public function getDonationList()
    {
        $dModel = new DonationModel();
        $data = $dModel->select();
        $this->ajaxReturn(qc_json_success($data));
    }

}