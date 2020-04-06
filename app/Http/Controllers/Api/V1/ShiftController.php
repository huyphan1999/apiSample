<?php
namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Shift;
use App\Api\Entities\User;
use App\Api\Repositories\Contracts\ShiftRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    protected $request;

    protected $shiftRepository;

    public function __construct(Request $request,ShiftRepository $shiftRepository)
    {
        $this->request=$request;
        $this->shiftRepository=$shiftRepository;
    }

    public function getAllShift()
    {
        $shift = $this->shiftRepository->all();
        return $shift;
    }

    public function create()
    {
        $validator = \Validator::make($this->request->all(), [
            'shift_name' => 'string|required',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i|after:start_at',
            'branch'=>'string|required',
            'dept'=>'string|required',
            'position'=>'string|required'
        ]);

        if ($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_name = $this->request->get('shift_name');
        $start_at = $this->request->get('start_at');
        $end_at = $this->request->get('end_at');

        $paramsExist = new Shift();
        $shiftExist = $this->shiftRepository->findByField('shift_name',$shift_name);

        $branch = $paramsExist->getBranch(mongo_id($this->request->get('branch')));
        $dept = $paramsExist->getDept(mongo_id($this->request->get('dept')));
        $position = $paramsExist->getPosition(mongo_id($this->request->get('position')));

        if(!empty($shiftExist))
        {
            foreach ($shiftExist as $item)
            {
                if($item->dept==$dept)
                {
                    return $this->errorBadRequest('Ca làm đã tồn tại!!');
                }
            }
        }

        if(empty($branch))
        {
            return $this->errorBadRequest('Chi nhánh không tồn tại');
        }
        if(empty($dept))
        {
            return $this->errorBadRequest('Phòng ban không tồn tại');
        }
        if(empty($position))
        {
            return $this->errorBadRequest('Chức danh không tồn tại');
        }

        $attributes = [
            'shift_name'=>$shift_name,
            'start_at'=>$start_at,
            'end_at'=>$end_at,
            'branch'=>$branch,
            'dept'=>$dept,
            'position'=>$position,
        ];

        $shift = $this->shiftRepository->create($attributes);
        return $this->successRequest($shift);
    }

    public function update()
    {
        $validator = \Validator::make($this->request->all(), [
            'shift_name' => 'string|required',
            'start_at' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start_at',
        ]);

        if ($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_name = $this->request->get('shift_name');
        $start_at = $this->request->get('start_at');
        $end_at = $this->request->get('end_at');

        $shiftExist = $this->shiftRepository->findByField('shift_name',$shift_name);

        if(!empty($shiftExist))
        {
            $attributes = [
                'start_at'=>$start_at,
                'end_at'=>$end_at,
            ];

            $shift = $this->shiftRepository->update($attributes,$shiftExist->_id);
            return $this->successRequest($shift);
        }

        return $this->errorBadRequest('Lỗi');
    }

    public function delete()
    {
        $validator = \Validator::make($this->request->all(), [
            'shift_name' => 'string|required',
        ]);

        if ($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_name = $this->request->get('shift_name');
        $shiftExist = $this->shiftRepository->findByField('shift_name',$shift_name);

        if(!empty($shiftExist))
        {
            $shiftExist->forceDelete();
            return $this->successRequest('Xóa thành công');
        }

        return $this->errorBadRequest('Lỗi');
    }

    public function list()
    {
        $validator = \Validator::make($this->request->all(), [
            'shift_name'=>'nullable|string',
            'start_at' => 'nullable|date_format:H:i',
            'end' => 'nullable|date_format:H:i|after:start_at',
            'branch'=>'string|nullable',
            'dept'=>'string|nullable',
            'position'=>'string|nullable'
        ]);

        if ($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_name = $this->request->get('shift_name');
        $start_at = $this->request->get('start_at');
        $end_at = $this->request->get('end_at');
        $branch = $this->request->get('branch');
        $dept = $this->request->get('dept');
        $position = $this->request->get('position');

        $params = [
            'shift_name'=>$shift_name,
            'start_at' => $start_at,
            'end' => $end_at,
            'branch'=>$branch,
            'dept'=>$dept,
            'position'=>$position
        ];

        $shift = $this->shiftRepository->getShift($params);
        return $shift;
    }
}