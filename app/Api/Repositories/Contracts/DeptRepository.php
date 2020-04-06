<?php

namespace App\Api\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface DeptRepository
 */
interface DeptRepository extends RepositoryInterface
{
    public function getDept($params=[],$limit = 0);
}
