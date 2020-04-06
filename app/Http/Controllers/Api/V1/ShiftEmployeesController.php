<?php
namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Shiftemployees;
use App\Api\Repositories\Contracts\ShiftemployeesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShiftEmployeesController extends Controller
{
    protected $request;

    protected $shiftemployeesRepository;

    public function __construct(Request $request,ShiftemployeesRepository $shiftemployeesRepository)
    {
        $this->request=$request;
        $this->shiftemployeesRepository=$shiftemployeesRepository;
    }

    //Lấy danh sách ca tất cả ca làm đã được chấm
    public function getEmployeesShift()
    {
        $shiftEmployee = $this->shiftemployeesRepository->all();
        return $this->successRequest($shiftEmployee);
    }

    public function registerShift()
    {
        $validator = \Validator::make($this->request->all(),[
            'shift_id'=>'string|required',
            'date'=>'required|date|date_format:Y-m-d',
            'user_id'=>'string|required',
            'dept_id'=>'string|required',
            'branch_id'=>'string|required',
            'position_id'=>'string|required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_id = $this->request->get('shift_id');
        $user_id = $this->request->get('user_id');
        $date = $this->request->get('date');
        $branch_id = $this->request->get('branch_id');
        $dept_id = $this->request->get('dept_id');
        $position_id = $this->request->get('position_id');

        $shiftExist = $this->shiftemployeesRepository->findByField([
            'shift_id'=>mongo_id($shift_id),
            'date'=>$date,
            'user_id'=>mongo_id($user_id),
            'branch_id'=>$branch_id,
            'dept_id'=>$dept_id,
            'position_id'=>mongo_id($position_id)])->first();

        if(empty($shiftExist))
        {
            $shiftEmploy = new Shiftemployees();

            try{
                $toCheckUser = $shiftEmploy->User($user_id);
                $toCheckShift = $shiftEmploy->Shift($shift_id);
                $toCheckBranch = $shiftEmploy->Branch($branch_id);
                $toCheckDept = $shiftEmploy->Dept($dept_id);
                $toCheckPosition = $shiftEmploy->Position($position_id);
            }
            catch (\Exception $ex)
            {
                return $this->errorBadRequest('Thông tin đăng kí ca làm không chính xác vui lòng kiểm tra lại!');
            }

            if(empty($toCheckUser))
            {
                return $this->errorBadRequest('Người dùng không tồn tại');
            }
            if(empty($toCheckShift))
            {
                return $this->errorBadRequest('Ca không tồn tại');
            }
            if(empty($toCheckDept))
            {
                return $this->errorBadRequest('Phòng ban không tồn tại');
            }
            if(empty($toCheckBranch))
            {
                return $this->errorBadRequest('Chi nhánh không tồn tại');
            }
            if(empty($toCheckPosition))
            {
                return $this->errorBadRequest('Chức danh không tồn tại');
            }

            $attributes = [
                'shift_id'=>mongo_id($shift_id),
                'date'=>$date,
                'user_id'=>mongo_id($user_id),
                'branch_id'=>mongo_id($branch_id),
                'dept_id'=>mongo_id($dept_id),
                'position_id'=>mongo_id($position_id),
                'check_in'=>'N/A',
                'check_out'=>'N/A',
                'sub_time'=>0,
                'status'=>false,
            ];

            $entryShift = $this->shiftemployeesRepository->create($attributes);
            return $this->successRequest($entryShift);
        }
    }

    public function checkIn()
    {
        $validator = \Validator::make($this->request->all(),[
           'shift_id'=>'string|required',
            'date'=>'required|date|date_format:Y-m-d',
            'user_id'=>'string|required',
            'branch_id'=>'string|required',
            'dept_id'=>'string|required',
            'position_id'=>'string|required',
            'check_in'=>'required|date_format:H:i',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_id = $this->request->get('shift_id');
        $user_id = $this->request->get('user_id');
        $branch_id = $this->request->get('branch_id');
        $dept_id = $this->request->get('dept_id');
        $position_id = $this->request->get('position_id');
        $check_in = $this->request->get('check_in');
        $date = $this->request->get('date');

        $shiftExist = $this->shiftemployeesRepository->findByField([
            'shift_id'=>mongo_id($shift_id),
            'date'=>$date,
            'user_id'=>mongo_id($user_id),
            'branch_id'=>mongo_id($branch_id),
            'dept_id'=>mongo_id($dept_id),
            'position_id'=>mongo_id($position_id)])->first();


        if(!empty($shiftExist))
        {
            $attributes = [
                'check_in'=>$check_in
            ];

            $checked_in = $this->shiftemployeesRepository->update($attributes,$shiftExist->_id);
            return $this->successRequest($checked_in);
        }
        return $this->errorBadRequest('Chưa tạo công');
    }

    public function checkOut()
    {
        $validator = \Validator::make($this->request->all(),[
            'shift_id'=>'string|required',
            'date'=>'required|date|date_format:Y-m-d',
            'user_id'=>'string|required',
            'branch_id'=>'string|required',
            'dept_id'=>'string|required',
            'position_id'=>'string|required',
            'check_out'=>'required|date_format:H:i',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shift_id = $this->request->get('shift_id');
        $user_id = $this->request->get('user_id');
        $branch_id = $this->request->get('branch_id');
        $dept_id = $this->request->get('dept_id');
        $check_out = $this->request->get('check_out');
        $date = $this->request->get('date');
        $position_id = $this->request->get('position_id');

        $shiftExist = $this->shiftemployeesRepository->findByField([
            'shift_id'=>mongo_id($shift_id),
            'date'=>$date,
            'user_id'=>mongo_id($user_id),
            'branch_id'=>mongo_id($branch_id),
            'dept_id'=>mongo_id($dept_id),
            'position_id'=>mongo_id($position_id)])->first();

        if(!empty($shiftExist))
        {
            if($shiftExist->check_in != 'N/A')
            {
                $attributes = [
                    'check_out'=>$check_out
                ];

                $checked_out = $this->shiftemployeesRepository->update($attributes,$shiftExist->_id);
                return $this->successRequest($checked_out);
            }
            return $this->errorBadRequest('Chưa chấm công');
        }
        return $this->errorBadRequest('Chưa tạo công');
    }

    public function confirmShift()
    {
        $validator = \Validator::make($this->request->all(),[
            'shift_id'=>'string|required',
            'date'=>'required|date|date_format:Y-m-d',
            'user_id'=>'string|required',
            'branch_id'=>'string|required',
            'dept_id'=>'string|required',
            'position_id'=>'string|required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $date = $this->request->get('date');
        $shift_id = $this->request->get('shift_id');
        $user_id = $this->request->get('user_id');
        $branch_id = $this->request->get('branch_id');
        $dept_id = $this->request->get('dept_id');
        $position_id = $this->request->get('position_id');

        $hasRecord = $this->shiftemployeesRepository->findByField([
            'shift_id' => mongo_id($shift_id),
            'date' => $date,
            'user_id' => mongo_id($user_id),
            'branch_id'=>mongo_id($branch_id),
            'dept_id'=>mongo_id($dept_id),
            'position_id'=>mongo_id($position_id)])->first();

        if(!empty($hasRecord))
        {
            if(($hasRecord->check_in != 'N/A') && ($hasRecord->check_out != 'N/A'))
            {
                if($hasRecord->status == false)
                {
                    $sub_time = (strtotime($hasRecord->check_out) - strtotime($hasRecord->check_in)) / 3600;
                    $attributes = [
                        'status'=>true,
                        'sub_time'=>$sub_time
                    ];

                    $confirm = $this->shiftemployeesRepository->update($attributes,$hasRecord->_id);

                    return $this->successRequest($confirm);
                }
                return $this->errorBadRequest('Ca đã được duyệt');
            }
        }
        return $this->errorBadRequest('Nhân viên chưa chấm công');
    }

    public function list()
    {
        $validator = \Validator::make($this->request->all(),[
            'shift_id'=>'string|nullable',
            'date'=>'nullable|date|date_format:Y-m-d',
            'user_id'=>'string|nullable',
            'branch_id'=>'string|nullable',
            'dept_id'=>'string|nullable',
            'position_id'=>'string|nullable'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $date = $this->request->get('date');
        $shift_id = $this->request->get('shift_id');
        $user_id = $this->request->get('user_id');
        $branch_id = $this->request->get('branch_id');
        $dept_id = $this->request->get('dept_id');
        $position_id = $this->request->get('position_id');
        $isHistory = $this->request->get('is_history');

        $params = [
            'date'=>$date,
            'shift_id'=>$shift_id,
            'user_id'=>$user_id,
            'branch_id'=>$branch_id,
            'dept_id'=>$dept_id,
            'position_id'=>$position_id,
            'is_history'=>$isHistory
        ];

        $get_shift = $this->shiftemployeesRepository->getShift($params);

        return $get_shift;
    }
}