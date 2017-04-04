<?php

// for debug
function debug()
{
    $arr = func_get_args();
    if (count($arr, COUNT_NORMAL) == 1) {
        print_r($arr[0]);
    } else {
        print_r($arr);
    }
}

function debug_exit($data)
{
    $arr = func_get_args();
    if (count($arr, COUNT_NORMAL) == 1) {
        print_r($arr[0]);
    } else {
        print_r($arr);
    }
    exit;
}

function http_get($ch, $url, $header = false)
{
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => TRUE,            //表示需要response header
        CURLOPT_RETURNTRANSFER => TRUE, //不让拿到的内容直接打印出来
        CURLOPT_TIMEOUT => 300,            //设置超时时间为5分钟
//		CURLOPT_NOBODY => TRUE,			//表示不需要response body
//		CURLOPT_FOLLOWLOCATION	=> 1
    );
    curl_setopt_array($ch, $options);
    if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $result = curl_exec($ch);
    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
        return $result;
    }
    return NULL;
}

function http_post($ch, $url, $data, $header)
{
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_FOLLOWLOCATION => 1        //设置curl支持页面链接跳转
    );
    curl_setopt_array($ch, $options);
    if (!empty($header)) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    return $output;
}

function qc_json_error($msg = 'operate error', $error_code = 40000)
{
    return array('error_code' => $error_code, 'msg' => $msg);
}

function qc_json_success($data = 'operate successfully', $code = 20000)
{
    return array('code' => $code, 'response' => $data);
}

function qc_json_error_request($data = 'request method error', $code = 40001)
{
    return array('code' => $code, 'response' => $data);
}

function is_password_pattern($password)
{
    if (!is_string($password)) {
        return false;
    }
    $pattern = "/^[0-9a-zA-Z]{6,20}$/";
    return preg_match($pattern, $password);
}

function is_phone_pattern($phone)
{
    if (!is_string($phone)) {
        return false;
    }
    $pattern = "/^1[0-9]{10}$/";
    return preg_match($pattern, $phone);
}

function split_str($str = '', $split_pos = 0)
{
    $left = substr($str, 0, $split_pos);
    $right = substr($str, $split_pos);
    return array($left, $right);
}

function array_del_by_val(array &$arr, $delVal)
{
    if (is_array($arr)) {
        foreach ($arr as $key => $value)
            if ($value == $delVal) {
                unset($arr[$key]);
                return true;
            }
    }
    return false;
}

function regex_confirm_patterns($info, $confirm_patterns = array())
{
    foreach ($confirm_patterns as $key => $val) {
        if (isset($info[$key])) {
            $flag = preg_match($val, $info[$key]);
            if (!$flag) {
                return false;
            }
        }
    }
    return true;
}