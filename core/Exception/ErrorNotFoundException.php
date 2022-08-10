<?php

namespace core\Exception;

class ErrorNotFoundException extends \Exception
{
    public function __construct($massage = 'Page Not Found', $code = 404)
    {
        parent::__construct($massage, $code);
    }
}
