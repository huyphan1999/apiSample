<?php

namespace App\Api\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Api\Repositories\Contracts\BranchRepository;
use App\Api\Repositories\Contracts\DepRepository;
use App\Api\Entities\Dep;
use App\Api\Validators\BranchValidator;

/**
 * Class BranchRepositoryEloquent
 */
class DepRepositoryEloquent extends BaseRepository implements DepRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Dep::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
    }
}
