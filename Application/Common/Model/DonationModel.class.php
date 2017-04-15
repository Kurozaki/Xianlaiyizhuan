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

    public function createDonation($info)
    {
        $add = $this->add($info);
        return $add;
    }

}