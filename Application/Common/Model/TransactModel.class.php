<?php
/**
 * Created by PhpStorm.
 * User: Kurozaki
 * Date: 2017/3/17
 * Time: 21:55
 */

namespace Common\Model;


class TransactModel extends BaseModel
{
    function __construct()
    {
        parent::__construct('transact', $this->tablePrefix, $this->connection);
    }

    public function editTransactPics($seller_id = 0, $edit_id = 0, $opStr = '', $uploadPaths = array())
    {
        $data = $this->where("id = %d and %seller_id = %d", $edit_id, $seller_id);
        if (!$data) {
            return false;
        }
        $opPattern = "//^([ud][\\d]+)(-[ud][\\d]+){0,9}$";
        if (!preg_match($opPattern, $opStr)) {
            return false;
        }

        $pathStr = $data['pics'];
        $pathArr = explode('|', $pathStr);
        $opArr = explode('-', $opStr);

        reset($uploadPaths);
        $next = true;
        foreach ($opArr as $_op) {
            if (!$next) {
                break;
            }
            $index = intval($_op[1]);
            switch ($_op[0]) {
                case 'u':
                    if ($index >= count($pathArr, COUNT_NORMAL)) {
                        array_push($pathArr, current($uploadPaths));
                    } else {
                        $pathArr[$index] = current($uploadPaths);
                    }
                    $next = next($uploadPaths);
                    break;

                case 'd':
                    if ($index < count($pathArr, COUNT_NORMAL)) {
                        unset($pathArr[$index]);
                    }
                    break;

                default:
                    break;
            }
        }
        $newPicsStr = '';
        foreach ($pathArr as $str) {
            $newPicsStr .= $str . '|';
        }
        $newPicsStr = substr($newPicsStr, strlen($newPicsStr) - 1);
        return $this->save(['pics' => $newPicsStr]);
    }

    public function giveLike($tra_id)
    {
        $find = $this->where("id = %d", $tra_id)->find();
        if ($find) {
            return -1;
        }
        $like = intval($find['likec']);
        return $this->save(['likec' => ($like + 1)]);
    }
}