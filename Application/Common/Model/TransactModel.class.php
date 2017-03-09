<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/3/9
 * Time: 19:54
 */

namespace Common\Model;


class TransactModel extends BaseModel{

    function __construct()
    {
        parent::__construct('user', $this->tablePrefix, $this->connection);
    }
}