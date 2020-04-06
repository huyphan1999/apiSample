<?php

namespace App\Api\Entities;

use Moloquent\Eloquent\Model as Moloquent;
use App\Api\Transformers\ShiftemployeesTransformer;
use Moloquent\Eloquent\SoftDeletes;

class Shiftemployees extends Moloquent
{
    use SoftDeletes;

    protected $collection = 'shiftEmployees';

    protected $fillable = ['shift_id', 'date', 'user_id', 'branch_id', 'dept_id', 'check_in', 'check_out', 'position_id', 'sub_time', 'status'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function transform()
    {
        $transformer = new ShiftemployeesTransformer();

        return $transformer->transform($this);
    }

    public function Shift($shift_id)
    {
        return Shift::where('_id',mongo_id($shift_id))->first();
    }
    public function User($user_id)
    {
        return User::where('_id',mongo_id($user_id))->first();
    }
    public function Branch($branch_id)
    {
        return Branch::where('_id',mongo_id($branch_id))->first();
    }
    public function Dept($dept_id)
    {
        return Dept::where('_id',mongo_id($dept_id))->first();
    }
    public function Position($position_id)
    {
        return Position::where('_id',mongo_id($position_id))->first();
    }

}
