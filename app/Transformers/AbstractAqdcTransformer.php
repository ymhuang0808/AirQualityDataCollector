<?php

namespace App\Transformers;


use App\Exceptions\TransformException;
use League\Fractal\TransformerAbstract;

abstract class AbstractAqdcTransformer extends TransformerAbstract
{
    /**
     * @param \stdClass $data
     * @return RemoteModel
     * @throws TransformException
     */
    abstract public function transform(\stdClass $data): RemoteModel;
}
