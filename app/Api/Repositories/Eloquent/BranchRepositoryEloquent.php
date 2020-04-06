<?php

namespace App\Api\Repositories\Eloquent;

use App\Api\Criteria\BranchCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\UserRepository;
use App\Api\Repositories\Contracts\branchRepository;
use App\Api\Entities\Branch;
use App\Api\Validators\BranchValidator;

/**
 * Class BranchRepositoryEloquent
 */
class BranchRepositoryEloquent extends BaseRepository implements BranchRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Branch::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }

    public function getBranch($params=[],$limit=0)
    {
        if(!empty($params['is_active']))
        {
            $item = $this->findByField('active',true);
        }
        elseif (!empty($params['is_paginate']))
        {
            $item = $this->paginate();
        }
        else
        {
            $item = $this->all();
        }

        return $item;
    }
}
