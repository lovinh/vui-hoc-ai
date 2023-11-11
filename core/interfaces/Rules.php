<?php

namespace app\core\http_context;

interface IRules
{
    public function validate($args = []);
}
