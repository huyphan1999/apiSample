<?php
namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Branch;
use App\Api\Repositories\Contracts\BranchRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BranchController extends Controller
{
    protected $request;
    protected $branchRepository;

    public function __construct(Request $request,BranchRepository $branchRepository)
    {
        $this->request=$request;
        $this->branchRepository=$branchRepository;
    }

    public function create()
    {
        $validator = \Validator::make($this->request->all(),[
            'branch_name'=>'string|required',
            'shop_id'=>'string|nullable',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $checkBranch = $this->branchRepository->findByField('branch_name',$this->request->get('branch_name'))->first();
        if(empty($checkBranch))
        {
           $attributes = [
               'branch_name'=>$this->request->get('branch_name'),
               'shop_id'=>$this->request->get('shop_id'),
               'active'=>true,
           ];

           $newbranch = $this->branchRepository->create($attributes);
           return $this->successRequest($newbranch);
        }
        return $this->errorBadRequest('Branch đã tồn tại');
    }

    public function update()
    {
//        $branchExist = Branch::where('_id',mongo_id($this->_id))->first();
//        return $branchExist;
        $validator = \Validator::make($this->request->all(),[
            'branch_name'=>'string|required',
            //'shop_id'=>'string|nullable',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $branchExist = $this->branchRepository->findByField('branch_name',$this->request->get('branch_name'))->first();

        if(!empty($branchExist))
        {
            if($branchExist->active == true)
            {
                $active = false;
            }
            else
            {
                $active = true;
            }
            $attributes = [
                'active'=>$active,
            ];

            $branch = $this->branchRepository->update($attributes,$branchExist->_id);
            return $this->successRequest($branch);
        }
        return $this->errorBadRequest('Branch không tồn tại');
    }

    public function delete()
    {
        $validator = \Validator::make($this->request->all(),[
            'branch_id'=>'string|required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $branch_id = mongo_id($this->request->get('branch_id'));
        $branchExist = $this->branchRepository->findByField('_id',$branch_id)->first();
        if(!empty($branchExist))
        {
            $branchExist->forceDelete();
            return $this->successRequest('Đã xóa');
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function list()
    {
        $validator = \Validator::make($this->request->all(),[
            'is_active'=>'string|nullable',
            'is_paginate'=>'string|nullable',
            'is_detail'=>'string|nullable'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $is_active = $this->request->get('is_active');
        $is_paginate = $this->request->get('is_paginate');
        $is_detail = $this->request->get('is_detail');

        $params = [
            'is_active'=>$is_active,
            'is_paginate'=>$is_paginate,
            'is_detail'=>$is_detail
        ];

        $branch = $this->branchRepository->getBranch($params);
        return $branch;
    }
}