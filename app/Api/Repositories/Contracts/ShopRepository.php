<?php

namespace App\Api\Repositories\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ShopRepository
 */
interface ShopRepository extends RepositoryInterface
{
    public function getShop($params = [],$limit = 0);
}
