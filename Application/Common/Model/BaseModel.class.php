<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/3/9
 * Time: 12:10
 */

namespace Common\Model;

use Think\Model;

class BaseModel extends Model
{
    protected $connection = array(
        'db_type' => 'mysql',
        'db_host' => '139.199.195.54',
        'db_user' => 'root',
        'db_pwd' => 'kurozaki',
        'db_name' => 'db_xlyz');

    protected $tablePrefix = 'xlyz_';
}