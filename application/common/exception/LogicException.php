<?php
namespace app\common\exception;

use Exception;

class LogicException extends Exception
{
    private $data;
    public function __construct($errorMsg="logic exception",$errorCode=10000,$data=[])
    {
        parent::__construct($errorMsg,$errorCode);
        $this->data=$data;
    }
    public function getData(){
        return $this->data;
    }
}