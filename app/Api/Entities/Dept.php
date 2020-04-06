<?php

namespace App\Api\Entities;

use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\DeptTransformer;
use Moloquent\Eloquent\SoftDeletes;

class Dept extends Moloquent
{
	use SoftDeletes;

	protected $collection = 'department';

    protected $fillable = ['dept_name','leader_name','address','branch'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transform()
    {
        $transformer = new DeptTransformer();

        return $transformer->transform($this);
    }

}
