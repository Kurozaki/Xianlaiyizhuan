<?php

function is_invalid_param($param)
{
    return is_null($param) || '' == $param;
}

function qc_json_success($msg = '', $data = null)
{
    $jsonData = array('code' => 20000, 'msg' => $msg);
    if (!is_null($data))
        $jsonData['data'] = $data;
    return $jsonData;
}

function qc_json_error($msg = '', $err_code = 40000)
{
    return array('code' => $err_code, 'msg' => $msg);
}

function is_legal_password($password = '')
{
    $pattern = "/^[0-9a-zA-Z]{6,20}$/";
    return preg_match($password, $password);
}