<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
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

    protected function giveLikeToPost($user_id, $p_id, $post_type)
    {
        $model = new GiveLikeModel();
        $give = $model->giveLike($user_id, $p_id, $post_type);
        return $give;
    }
}