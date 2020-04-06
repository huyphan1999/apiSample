<?php

namespace App\Api\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
/**
 * Class ShiftemployeeCriteria
 */
class ShiftemployeeCriteria implements CriteriaInterface
{
    protected $params;
    public function __construct($params = [])
    {
        $this->params = $params;
    }
    
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $query = $model->newQuery();

        if(!empty($this->params['date']))
        {
            $query->where('date',$this->params['date'])->get();
        }
        if(!empty($this->params['user_id']))
        {
            $query->where('user_id',mongo_id($this->params['user_id']))->get();
        }
        if(!empty($this->params['branch_id']))
        {
            $query->where('branch_id',mongo_id($this->params['branch_id']))->get();
        }
        if(!empty($this->params['dept_id']))
        {
            $query->where('dept_id',mongo_id($this->params['dept_id']))->get();
        }
        if(!empty($this->params['shift_id']))
        {
            $query->where('shift_id',mongo_id($this->params['shift_id']))->get();
        }
        
        return $query;
    }
}
