<?php

namespace App\Api\Repositories\Eloquent;

use App\Api\Criteria\DeptCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\deptRepository;
use App\Api\Entities\Dept;
use App\Api\Validators\DeptValidator;

/**
 * Class DeptRepositoryEloquent
 */
class DeptRepositoryEloquent extends BaseRepository implements DeptRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Dept::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }

    public function getDept($params=[],$limit = 0)
    {
        $this->pushCriteria(new DeptCriteria($params));

        if(!empty($params['is_detail']))
        {
            $item = $this->get()->first();
        }
        elseif (!empty($params['is_paginate']))
        {
            $item = $this->paginate();
        }
        else
        {
            $item = $this->all();
        }

        $this->popCriteria(new DeptCriteria($params));
        return $item;
    }
}
