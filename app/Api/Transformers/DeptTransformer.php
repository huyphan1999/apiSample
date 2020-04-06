<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Dept;

/**
 * Class DeptTransformer
 */
class DeptTransformer extends TransformerAbstract
{

    /**
     * Transform the \Dept entity
     * @param \Dept $model
     *
     * @return array
     */
    public function transform(Dept $model)
    {
        return [
            'id'         => $model->_id,

            

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
