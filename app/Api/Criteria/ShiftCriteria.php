<?php

namespace App\Api\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
/**
 * Class ShiftCriteria
 */
class ShiftCriteria implements CriteriaInterface
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

        if(!empty($this->params['start_at']))
        {
            $query->where('start_at',$this->params['start_at'])->get();
        }
        if(!empty($this->params['end_at']))
        {
            $query->where('end_at',$this->params['end_at'])->get();
        }
        if(!empty($this->params['branch']))
        {
            $query->where('branch',$this->params['branch'])->get();
        }
        if(!empty($this->params['dept']))
        {
            $query->where('dept',$this->params['dept'])->get();
        }
        if(!empty($this->params['position']))
        {
            $query->where('position',$this->params['position'])->get();
        }
        if(!empty($this->params['shift_name']))
        {
            $query->where('shift_name',$this->params['shift_name'])->get();
        }
        
        return $query;
    }
}
