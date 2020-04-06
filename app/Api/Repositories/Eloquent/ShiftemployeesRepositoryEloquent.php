<?php

namespace App\Api\Repositories\Eloquent;

use App\Api\Criteria\ShiftemployeeCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\shiftemployeesRepository;
use App\Api\Entities\Shiftemployees;
use App\Api\Validators\ShiftemployeesValidator;

/**
 * Class ShiftemployeesRepositoryEloquent
 */
class ShiftemployeesRepositoryEloquent extends BaseRepository implements ShiftemployeesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Shiftemployees::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }

    public function getShift($params=[],$limit=0)
    {
        $this->pushCriteria(new ShiftemployeeCriteria($params));

        if(!empty($params['is_detail']))
        {
            $item = $this->get()->first();
        }
        elseif (!empty($params['is_paginate']))
        {
            $item = $this->paginate();
        }
        elseif (!empty($params['is_history']))
        {
            $item = $this->findByField(['status'=>true,'user_id'=>mongo_id($params['user_id'])]);
        }
        else
        {
            $item = $this->all();
        }

        return $item;
    }
}
