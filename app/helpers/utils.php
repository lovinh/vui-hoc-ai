<?php

namespace app\core\helper;

function toSlug($str)
{
    return $str;
}

function mask($str, $first, $last)
{
    $len = strlen($str);
    $toShow = $first + $last;
    return substr($str, 0, $len <= $toShow ? 0 : $first) . str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)) . substr($str, $len - $last, $len <= $toShow ? 0 : $last);
}

function mask_email($email)
{
    $mail_parts = explode("@", $email);
    $domain_parts = explode('.', $mail_parts[1]);

    $mail_parts[0] = mask($mail_parts[0], 2, 1); // show first 2 letters and last 1 letter
    $domain_parts[0] = mask($domain_parts[0], 2, 1); // same here
    $mail_parts[1] = implode('.', $domain_parts);

    return implode("@", $mail_parts);
}

function add_pad(int $value)
{
    return $value < 10 ? ($value > 0 ? str_pad(strval($value), 2, "0", STR_PAD_LEFT) : $value) : $value;
}

function precent(int $first, int $second)
{
    return $second != 0 ? ($first * 100) / $second : 0;
}
