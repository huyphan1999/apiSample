<?php

namespace App\Api\Repositories\Eloquent;

use App\Api\Criteria\ShopCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\shopRepository;
use App\Api\Entities\Shop;
use App\Api\Validators\ShopValidator;

/**
 * Class ShopRepositoryEloquent
 */
class ShopRepositoryEloquent extends BaseRepository implements ShopRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Shop::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }

    public function getShop($params = [],$limit = 0) {
        $this->pushCriteria(new ShopCriteria($params));
        if(!empty($params['is_detail'])) {
            $item = $this->get()->first();
        } elseif(!empty($params['is_paginate'])) {
            $item = $this->paginate();
        } else {
            $item = $this->all();
        }
        $this->popCriteria(new ShopCriteria($params));
        return $item;
    }
}
