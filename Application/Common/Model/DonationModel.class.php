<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/4/13
 * Time: 21:27
 */

namespace Common\Model;


class DonationModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('donate', $this->tablePrefix, $this->connection);
    }

    public function createDonationInfo($info)
    {
        $add = $this->add($info);

        if ($add) {
            $recent = F('recent_donation');
            if (!$recent) {
                $recent = [];
            }

            array_unshift($recent, $add);
            if (count($recent, COUNT_NORMAL) > 15)  //max 15 records
                array_pop($recent);

            F('recent_donation', $recent);
        }

        return $add;
    }


    public function deleteDonationInfo($delId)
    {
        $delFlag = $this->where("id = %d", $delId)->delete();

        if ($delFlag) {

            $recent = F('recent_donation');
            if ($recent) {
                array_del_by_val($recent, $delId);
                F('recent_donation', $recent);
            }
        }

        return $delFlag;
    }

    public function recentDonationList()
    {
        $recent = F('recent_donation');
        if (!$recent) {
            return null;
        }

        $condition = ['id' => ['in', $recent]];
        $data = $this->where($condition)->select();
        if (!$data) {
            return null;
        }

        foreach ($data as &$info) {
            $info['ac_pic'] = C('BASE_URL') . $info['ac_pic'];
        }

        return $data;
    }

    public function giveLike($userId, $dn_id)
    {
        $find = $this->where("id = %d", $dn_id)->find();
        if ($find) {
            $give = $this->giveLikeToPost($userId, $dn_id, C('COMMENT_TYPE_DONATION'));
            if ($give) {
                $likec = $find['likec'] + 1;

                $save = $this->where("id = %d", $dn_id)->save(['likec' => $likec]);

                return $save ? $likec : false;
            } else
                return $give;
        }
        return false;
    }
}