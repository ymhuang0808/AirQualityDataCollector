<?php
/**
 * Created by PhpStorm.
 * User: aming
 * Date: 2017/3/28
 * Time: 下午4:08
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

abstract class AbstractAqdcTransformer extends TransformerAbstract
{
    abstract public function transform(\stdClass $data): RemoteModel;
}
