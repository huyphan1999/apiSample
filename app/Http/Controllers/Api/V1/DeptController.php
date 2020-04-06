<?php
namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Dept;
use App\Api\Repositories\Contracts\DeptRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeptController extends Controller
{
    protected $request;

    protected $deptRepository;

    public function __construct(Request $request,DeptRepository $deptRepository)
    {
        $this->request=$request;
        $this->deptRepository=$deptRepository;
    }

    public function create()
    {
        $validator = \Validator::make($this->request->all(),[
            'dept_name'=>'string|required',
            'leader_name'=>'string|required',
            'address'=>'string',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }


        $dept_name = $this->request->get('dept_name');
        $leader_name = $this->request->get('leader_name');
        $branch_id = mongo_id($this->request->get('address'));
        $deptExist = $this->deptRepository->findByField('dept_name',$this->request->get('dept_name'))->first();

        if(empty($deptExist))
        {
            $attributes = [
                'dept_name'=> $dept_name,
                'leader_name'=> $leader_name,
                'address'=> $branch_id,
            ];

            $dept = $this->deptRepository->create($attributes);
            return $this->successRequest($dept);
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function update()
    {
        $validator = \Validator::make($this->request->all(),[
            'dept_name'=>'string|required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $deptExist = $this->deptRepository->findByField('dept_name',$this->request->get('dept_name'))->first();
        if(!empty($deptExist))
        {
            $attributes = [
                'leader_name'=>$this->request->get('leader_name'),
                'address'=>$this->request->get('address'),
            ];

            $dept = $this->deptRepository->update($attributes,$deptExist->_id);
            return $this->successRequest($deptExist);
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function delete()
    {
        $validator = \Validator::make($this->request->all(),[
            'dept_name'=>'string|required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $deptExist = $this->deptRepository->findByField('dept_name',$this->request->get('dept_name'))->first();
        if(!empty($deptExist))
        {
            $deptExist->forceDelete();
            return $this->successRequest('Xóa thành công');
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function list()
    {
        $validator = \Validator::make($this->request->all(),[
           'address'=> 'required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }
        $address = $this->request->get('address');
        $params = [
            'address'=>$address,
        ];

        $getDept = $this->deptRepository->getDept($params);
        return $getDept;
    }
}