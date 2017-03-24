<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/22
 * Time: 21:35
 */

namespace Home\Controller;


use Common\Controller\BaseController;
use Common\Model\RequirementModel;

class RequirementController extends BaseController
{
    public function createRequirement()
    {
        $userId = $this->reqLogin();
        $reqInfo = $this->reqPost(array('intro', 'type', 'price'));

        $reqInfo['req_user'] = $userId;
        $reqInfo['ctime'] = time();

        $reqModel = new RequirementModel();
        $add = $reqModel->createReq($reqInfo);
        if ($add) {
            $reqInfo['id'] = $add;
            $this->ajaxReturn(qc_json_success($reqInfo));
        } else
            $this->ajaxReturn(qc_json_error('Failed to create this requirement'));
    }

    public function deleteRequirement()
    {
        $userId = $this->reqLogin();
        $del_id = I('post.del_id');
        $model = new RequirementModel();
        $delete = $model->deleteReq($del_id, $userId);
        $delete ? $this->ajaxReturn(qc_json_success('Delete success')) :
            $this->ajaxReturn(qc_json_error('Delete error'));
    }

    public function updateRequirementInfo()
    {
        $userId = $this->reqLogin();
        $updateInfo = $this->reqPost(array('req_id'), array('intro', 'type', 'price'));
        $reqId = $updateInfo['req_id'];
        unset($updateInfo['req_id']);
        if (count($updateInfo, COUNT_NORMAL) == 0) {
            $this->ajaxReturn(qc_json_error_request('Require more update param'));
        }
        $model = new RequirementModel();
        $save = $model->where("id = %d and req_user = %d", $reqId, $userId)->save($updateInfo);
        if ($save)
            $this->ajaxReturn(qc_json_success('Update success'));
        else
            $this->ajaxReturn(qc_json_error('Update failed'));
    }

    public function myRequirementList()
    {
        $userId = $this->reqLogin();
        $model = new RequirementModel();
        $data = $model->where(['req_user' => $userId])->select();
        $this->ajaxReturn(qc_json_success($data));
    }

    public function recentRequirementList()
    {
        $recentList = F('recent_req');
        $data = array();
        if (is_array($recentList)) {
            $model = new RequirementModel();
            $data = $model->where(['id' => ['in', $recentList]])->select();
        }
        $this->ajaxReturn(qc_json_success($data));
    }
}