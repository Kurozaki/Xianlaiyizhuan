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

        $data = $this->reqPost(array(
            'ac_title',
            'ac_time',
            'ac_addr',
            'ac_pay',
            'ac_chk_addr',
            'ac_detail',
            'ac_host',
            'ac_contact'
        ));

        $data['ctime'] = time();
        $data['has_comm'] = 0;
        $data['likec'] = 0;

        $upload = $this->uploadPictures('donation');

        if (!is_array($upload)) {
            $this->ajaxReturn(qc_json_error('Require a picture'));
        }

        $path = C('FILE_STORE_ROOT') . $upload['savepath'] . $upload['savename'];
        $path = substr($path, 2);
        $data['ac_pic'] = $path;

        $model = new DonationModel();
        $createId = $model->createDonationInfo($data);
        if ($createId) {

            $data['id'] = $createId;
            $data['ac_pic'] = C('BASE_URL') . $data['ac_pic'];

            $this->ajaxReturn(qc_json_success($data));
        } else {
            $this->ajaxReturn(qc_json_error('Create failed'));
        }
    }

    public function updateDonationInfo()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));
        $postData = $this->reqPost(
            array('dn_id'),
            array('ac_title',
                'ac_time',
                'ac_addr',
                'ac_pay',
                'ac_chk_addr',
                'ac_detail',
                'ac_host',
                'ac_contact'
            ));
        $_id = $postData['dn_id'];
        unset($postData['dn_id']);
        if (count($postData, COUNT_NORMAL) == 0) {
            $this->ajaxReturn(qc_json_error('Require at least one update param'));
        }

        $model = new DonationModel();
        $save = $model->where("id = %d", $_id)->save($postData);

        if ($save) {
            $this->ajaxReturn(qc_json_success('Update success'));
        } else {
            $this->ajaxReturn(qc_json_error('Update failed'));
        }
    }

    public function updateDonationPicture()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));

        $dn_id = I('post.dn_id');
        if (!$dn_id)
            $this->ajaxReturn(qc_json_error('Need param: dn_id'));

        $upload = $this->uploadPictures('donation');
        if (!is_array($upload)) {
            $this->ajaxReturn(qc_json_error($upload));
        }

        $path = C('FILE_STORE_ROOT') . $upload['savepath'] . $upload['savename'];

        $model = new DonationModel();
        $save = $model->where("id = %d", $dn_id)->save(['ac_pic' => substr($path, 2)]);

        if ($save) {
            $this->ajaxReturn(qc_json_success('Update success'));
        } else {
            unlink($path);
            $this->ajaxReturn(qc_json_error('Failed to update'));
        }
    }

    public function deleteDonationInfo()
    {
        $userId = $this->reqLogin();
        $this->reqUserWithPermission($userId, C('USER_PERM_SUPER'));
        $delId = I('post.del_id');

        $model = new DonationModel();
        $delFlag = $model->deleteDonationInfo($delId);

        if ($delFlag) {
            $this->ajaxReturn(qc_json_success('Delete success'));
        } else {
            $this->ajaxReturn(qc_json_error('Delete failed'));
        }
    }

    public function getDonationList()
    {
        $offset = I('post.offset');
        $offset = intval($offset);
        $userId = $this->onlineUserId();

        $model = new DonationModel();
        $data = $model->getDonationList($offset, C('COUNT_PAGING') + 1, $userId);

        if ($data) {

            $d_count = count($data, COUNT_NORMAL);
            $continue_load = 0;

            if ($d_count == C('COUNT_PAGING') + 1) {
                $continue_load = 1;
                $d_count--;
                array_pop($data);
            }

            $this->ajaxReturn(qc_json_success(array(
                'continue_load' => $continue_load,
                'offset' => $d_count,
                'data' => $data
            )));
        } else {
            $this->ajaxReturn(qc_json_null_data());
        }
    }


    public function giveLikeToDonation()
    {
        $userId = $this->reqLogin();
        $dn_id = I('post.dn_id');

        if (!$dn_id)
            $this->ajaxReturn(qc_json_error('Need param: dn_id'));

        $model = new DonationModel();
        $likec = $model->giveLike($userId, $dn_id);

        if ($likec) {
            $this->ajaxReturn(qc_json_success(['likec' => $likec]));
        } else {
            $this->ajaxReturn(qc_json_error('Failed'));
        }
    }
}
