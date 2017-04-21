<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/4/10
 * Time: 20:26
 */

namespace Common\Model;


class OrderModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('order', $this->tablePrefix, $this->connection);
    }

    public function createOrderInfo($info)
    {
        $price = floatval($info['price']);

        $uModel = new UserModel();
        $balanceFlag = $uModel->addBalance($info['buyer'], -1 * $price);
        if (!$balanceFlag) {
            return -1;
        }
        $existFlag = $this->where($info)->find();
        if ($existFlag) {
            return -2;
        }
        $create = $this->add($info);
        return $create;
    }

    public function finishOrder($orderId)
    {
        $flag = $this->where("id = %d", $orderId)->save(['status' => 1]);
        return $flag;
    }
}