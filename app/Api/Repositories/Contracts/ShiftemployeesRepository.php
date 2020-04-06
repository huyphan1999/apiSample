<?php

namespace App\Api\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ShiftemployeesRepository
 */
interface ShiftemployeesRepository extends RepositoryInterface
{
    public function getShift($params=[],$limit=0);
}
