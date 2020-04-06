<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Shiftemployees;

/**
 * Class ShiftemployeesTransformer
 */
class ShiftemployeesTransformer extends TransformerAbstract
{

    /**
     * Transform the \Shiftemployees entity
     * @param \Shiftemployees $model
     *
     * @return array
     */
    public function transform(Shiftemployees $model)
    {
        return [
            'id'         => $model->_id,

            

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
