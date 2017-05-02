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
        $reqInfo = $this->reqPost(array('intro', 'type', 'price', 'picstr'));

        //add default info
        $reqInfo['req_user'] = $userId;
        $reqInfo['ctime'] = time();
        $reqInfo['solve'] = 0;

        //decode pic base64 string to picture
        $pic_arr = explode(",", $reqInfo['picstr']);
        $pic_arr = array_slice($pic_arr, 0, 5);     //max 5 picture
        $saveRoot = C('FILE_STORE_ROOT') . "requirement/requirement_info/";     //file save root
        $picsPath = "";
        foreach ($pic_arr as $base64) {
            $path = $saveRoot . random_string() . ".jpg";
            $this->base64FileDecode($base64, $path);
            $picsPath .= (substr($path, 2) . "|");
        }
        $reqInfo['pics'] = substr($picsPath, 0, strlen($picsPath) - 1);
        unset($reqInfo['picstr']);

        $reqModel = new RequirementModel();
        $add = $reqModel->createReq($reqInfo);

        if ($add) {
            $reqInfo['id'] = $add;
            $reqInfo['pics'] = explode("|", $reqInfo['pics']);
            foreach ($reqInfo['pics'] as &$pic) {
                $pic = C('BASE_URL') . $pic;
            }

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

        if ($data) {
            //process the pic url
            foreach ($data as &$info) {
                $info['pics'] = explode("|", $info['pics']);
                foreach ($info['pics'] as &$url) {
                    $url = C('BASE_URL') . $url;
                }
            }

            $this->ajaxReturn(qc_json_success($data));
        } else {
            $this->ajaxReturn(qc_json_null_data());
        }
    }

    public function recentRequirementList()
    {
        $model = new RequirementModel();
        $data = $model->recentRequirementList();

        if ($data) {
            $this->ajaxReturn(qc_json_success($data));
        } else {
            $this->ajaxReturn(qc_json_null_data());
        }
    }

    public function getAllRequirementList()
    {
        $offset = I('post.offset');
        if (!$offset) {
            $offset = 0;
        } else
            $offset = intval($offset);

        $model = new RequirementModel();
        $data = $model->requirementList($offset, C('COUNT_PAGING'));

        if ($data) {
            $this->ajaxReturn(qc_json_success(array(
                'offset' => $offset + C('COUNT_PAGING'),
                'data' => $data
            )));
        } else
            $this->ajaxReturn(qc_json_null_data());
    }

    public function setToSolvedStatus()
    {
        $userId = $this->reqLogin();
        $reqId = I('post.req_id');
        $model = new RequirementModel();

        $flag = $model->where("id = %d and req_user = %d", $reqId, $userId)->save(['solve' => 1]);
        if ($flag) {
            $this->ajaxReturn(qc_json_success('Operate success'));
        } else {
            $this->ajaxReturn(qc_json_error('Failed to operate'));
        }
    }

    public function giveLikeToRequirement()
    {
        $userId = $this->reqLogin();
        $req_id = I('post.req_id');

        if (!$req_id)
            $this->ajaxReturn(qc_json_error('Need param: req_id'));

        $model = new RequirementModel();
        $give = $model->giveLike($userId, $req_id);

        if ($give) {
            $this->ajaxReturn(qc_json_success(['likec' => $give]));
        } else {
            $this->ajaxReturn(qc_json_error('Failed'));
        }
    }
}