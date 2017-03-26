<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/3/26
 * Time: 下午2:26
 */

namespace App\Transformers;


class RemoteModel
{
    public $fields;
    public $relationships;

    public function __construct($fields, $relationships = null)
    {
        $this->fields = $fields;
        $this->relationships = $relationships;
    }
}