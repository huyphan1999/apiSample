<?php

namespace App\Api\Entities;

use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\UserTransformer;
use Moloquent\Eloquent\SoftDeletes;

class User extends Moloquent
{
	use SoftDeletes;

	protected $collection = 'user';

    protected $fillable = ['full_name','email','phone_number','branch_name','dept_name','position_name','shop_name'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transform()
    {
        $transformer = new UserTransformer();

        return $transformer->transform($this);
    }

    public function branch($branch_id='')
    {
        return Branch::where('_id',mongo_id($branch_id))->first()->_id;
    }
    public function dept($dept_id='')
    {
        return Dept::where('_id',mongo_id($dept_id))->first()->_id;
    }
    public function shop($shop_id='')
    {
        return Shop::where('_id',mongo_id($shop_id))->first()->_id;
    }
    public function loginShop($shop_name='')
    {
        return Shop::where('shop_name',$shop_name)->first()->_id;
    }
    public function position($position_id='')
    {
        return Position::where('_id',mongo_id($position_id))->first()->_id;
    }
}
