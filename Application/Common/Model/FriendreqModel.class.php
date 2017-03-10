<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/10
 * Time: 19:51
 */

namespace Common\Model;


class FriendreqModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('friendreq', $this->tablePrefix, $this->connection);
    }


}