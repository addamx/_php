<?php
/**
 * 检查手机号码格式
 * @param $mobile 手机号码
 */
function check_mobile($mobile)
{
    if (preg_match('/1[34578]\d{9}$/', $mobile)) {
        return true;
    }

    return false;
}

/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }

    return false;
}
