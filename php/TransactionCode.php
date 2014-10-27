<?php

/**
 * Class TransactionCode 业务号生成器
 *
 */
class TransactionCode {
    protected  $_code_prefix;

    public function __construct($prefix='')
    {
        $this->setCodePrefix($prefix);
    }

    /**
     * 设置交易号前缀
     * @param $prefix
     */
    public function setCodePrefix($prefix)
    {
        $this->_code_prefix = $prefix;
    }

    public function generate()
    {
       $code = $this->_code_prefix . date('y') . dechex(date('m')) . date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return strtoupper($code);
    }
} 
