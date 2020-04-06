<?php
namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\User;
use App\Api\Repositories\Contracts\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $request;

    protected $userRepository;

    public function __construct(Request $request,UserRepository $userRepository)
    {
        $this->request=$request;
        $this->userRepository=$userRepository;
    }

    public function getAllUser()
    {
        $user = $this->userRepository->all();
        return $user;
    }

    public function register()
    {
        $validator = \Validator::make($this->request->all(),[
           'full_name'=>'string|required',
           'email'=>'required|email|unique:user',
            'phone_number'=>'string|required|unique:user',
            'branch_name'=>'string|required',
            'dept_name'=>'string|required',
            'position_name'=>'string|required',
            'shop_name'=>'string',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $elementExist = new User();

        $full_name = $this->request->get('full_name');
        $email = $this->request->get('email');
        $phone_number = $this->request->get('phone_number');
        $branch_name = $elementExist->branch(mongo_id($this->request->get('branch_name')));
        $dept_name = $elementExist->dept(mongo_id($this->request->get('dept_name')));
        $position_name = $elementExist->position(mongo_id($this->request->get('position_name')));
        $shop_name = $elementExist->shop(mongo_id($this->request->get('shop_name')));

        if(empty($branch_name))
        {
            return $this->errorBadRequest('Chi nhánh không tồn tại');
        }
        if(empty($dept_name))
        {
            return $this->errorBadRequest('Phòng ban không tồn tại');
        }
        if(empty($shop_name))
        {
            return $this->errorBadRequest('Cửa hàng không tồn tại');
        }
        if(empty($position_name))
        {
            return $this->errorBadRequest('Chức danh không tồn tại');
        }

        $attributes = [
            'full_name'=>$full_name,
            'email'=>$email,
            'phone_number'=>$phone_number,
            'branch_name'=>$branch_name,
            'dept_name'=>$dept_name,
            'position_name'=>$position_name,
            'shop_name'=>$shop_name,
        ];

        $user = $this->userRepository->create($attributes);
        return $this->successRequest($user);
    }

    public function update()
    {
        $validator = \Validator::make($this->request->all(),[
            'full_name'=>'string|required',
            'email'=>'required|email',
            'phone_number'=>'string|required',
            'branch_name'=>'string|required',
            'dept_name'=>'string|required',
            'position_name'=>'string|required',
            'shop_name'=>'required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $full_name = $this->request->get('full_name');
        $email = $this->request->get('email');
        $phone_number = $this->request->get('phone_number');

        $userExist = $this->userRepository->findByField('full_name',$full_name)->first();

        if(!empty($userExist))
        {
            $branch_name = $userExist->branch(mongo_id($this->request->get('branch_name')));
            $dept_name = $userExist->dept(mongo_id($this->request->get('dept_name')));
            $position_name = $userExist->position(mongo_id($this->request->get('position_name')));

            $shop_name = $userExist->shop(mongo_id($this->request->get('shop_name')));


            if(empty($branch_name))
            {
                return $this->errorBadRequest('Chi nhánh không tồn tại');
            }
            if(empty($dept_name))
            {
                return $this->errorBadRequest('Phòng ban không tồn tại');
            }
            if(empty($shop_name))
            {
                return $this->errorBadRequest('Cửa hàng không tồn tại');
            }
            if(empty($position_name))
            {
                return $this->errorBadRequest('Chức danh không tồn tại');
            }

            $attributes = [
                'email'=>$email,
                'phone_number'=>$phone_number,
                'branch_name'=>$branch_name,
                'dept_name'=>$dept_name,
                'position_name'=>$position_name,
                'shop_name'=>$shop_name,
            ];

            $user = $this->userRepository->update($attributes,$userExist->_id);
            return $this->successRequest($user);
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function delete()
    {
        $validator = \Validator::make($this->request->all(),[
            'full_name'=>'string|required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $full_name = $this->request->get('full_name');
        $userExist = $this->userRepository->findByField('full_name',$full_name)->first();

        if(!empty($userExist))
        {
            $userExist->forceDelete();
            return $this->successRequest('Xóa thành công');
        }
        return $this->errorBadRequest('Lỗi');
    }

    public function login()
    {
//        $value = $this->request->session()->get('shop_name', function() {
//            return 'shop_name';
//        });
        $validator = \Validator::make($this->request->all(),[
            'shop_name'=>'required',
            'phone_number'=>'required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }
        $user = new User();
        $shopName = $user->loginShop($this->request->get('shop_name'));
        $checkShopName = $this->userRepository->findByField('shop_name',mongo_id($shopName));

        $phone_number = $this->request->get('phone_number');

        if(!isset($checkShopName))
        {
            return $this->errorBadRequest('User name không tồn tại');
        }

        foreach($checkShopName as $item)
        {
            if($item->phone_number == $phone_number)
            {
                //return $item->branch_name;
                $this->request->session()->put([
                    'shop_name'=>$this->request->get('shop_name'),
                    'phone_number'=>$this->request->get('phone_number')
                ]);
                return $this->successRequest($item);
            }
        }

        return $this->errorBadRequest('Sai mật khẩu');
    }

    public function list()
    {
        $validator = \Validator::make($this->request->all(),[
            'branch_name'=>'string|nullable',
            'dept_name'=>'string|nullable',
            'position_name'=>'string|nullable',
            'shop_name'=>'string|nullable'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $branch_name = $this->request->get('branch_name');
        $dept_name = $this->request->get('dept_name');
        $position_name = $this->request->get('position_name');
        $shop_name = $this->request->get('shop_name');

        $params = [
            'branch_name'=>$branch_name,
            'dept_name'=>$dept_name,
            'position_name'=>$position_name,
            'shop_name'=>$shop_name
        ];

        $user = $this->userRepository->getUser($params);
        return $user;
    }
}