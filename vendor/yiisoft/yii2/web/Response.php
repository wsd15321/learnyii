<?php

namespace yii\web;


class Response extends \yii\base\Response
{

    public $stream = null;

    public $content;









    /**
     * 设置$ths->content内容
     */
    protected function prepare()
    {

    }


    protected function senContent()
    {
        if ($this->stream === null) {
            echo $this->content;
            exit;
        }
    }


}