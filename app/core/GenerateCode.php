<?php

namespace app\core\helper;

class GenerateCode
{
    public static function generate(int $n_digits)
    {
        return str_pad(rand(0, pow(10, $n_digits)-1), $n_digits, '0', STR_PAD_LEFT);
    }
}
