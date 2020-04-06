<?php

namespace App\Api\Entities;

use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\ShiftTransformer;
use Moloquent\Eloquent\SoftDeletes;

class Shift extends Moloquent
{
	use SoftDeletes;

	protected $collection = 'shift';

    protected $fillable = ['shift_name','start_at','end_at','branch','position','dept'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transform()
    {
        $transformer = new ShiftTransformer();

        return $transformer->transform($this);
    }

    public function getBranch($branch_id='')
    {
        return Branch::where('_id',mongo_id($branch_id))->first()->branch_name;
    }
    public function getDept($dept_id='')
    {
        return Dept::where('_id',mongo_id($dept_id))->first()->dept_name;
    }
    public function getPosition($position_id='')
    {
        return Position::where('_id',mongo_id($position_id))->first()->position;
    }
}
