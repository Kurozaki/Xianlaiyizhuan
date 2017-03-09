<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/3/9
 * Time: 12:12
 */

namespace Common\Model;


class UserModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('user', $this->tablePrefix, $this->connection);
    }

    public static function add_user($data)
    {
        $model = new self();
        return $model->add($data);
    }
}