<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\Entities\Shop;

/**
 * Class ShopTransformer
 */
class ShopTransformer extends TransformerAbstract
{

    /**
     * Transform the \Shop entity
     * @param \Shop $model
     *
     * @return array
     */
    public function transform(Shop $model)
    {
        return [
            'id'         => $model->_id,

            

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
