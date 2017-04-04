<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/30
 * Time: 10:03
 */

namespace Home\Controller;


use Common\Controller\BaseController;

class DonationController extends BaseController
{
    public function createDonation()
    {
        $userId = $this->reqLogin();
    }
}