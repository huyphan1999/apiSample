<?php

namespace App\Api\Entities;

use Illuminate\Http\Request;
use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\ShopTransformer;
use Moloquent\Eloquent\SoftDeletes;

class Shop extends Moloquent
{
	use SoftDeletes;

	protected $collection = 'shop';

    protected $fillable = ['shop_name','user_name','register_name','email','phone_number','branch_id'];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transform()
    {
        $transformer = new ShopTransformer();

        return $transformer->transform($this);
    }

    public function branch()
    {
        return Branch::where('shop_id',($this->_id))->first();
    }

    public function getUser($username)
    {
        return User::where('full_name',$username)->first();
    }
}
