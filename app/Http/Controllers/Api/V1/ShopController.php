<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Entities\Branch;
use App\Api\Entities\Shop;
use App\Api\Entities\User;
use App\Api\Repositories\Contracts\ShopRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    protected $request;
    protected $shopRepository;

    public function __construct(Request $request, ShopRepository $shopRepository)
    {
        $this->request=$request;
        $this->shopRepository=$shopRepository;
    }

    public function viewList()
    {
        $shop = $this->shopRepository->all();
        return $shop;
    }

    public function getShopByBranch()
    {

    }

    public function detail()
    {
        $shopExist = $this->shopRepository->findByField('shop_name',$this->request->get('shop_name'))->first();

        if(!empty($shopExist))
        {
            return $shopExist;
        }
    }

    public function create()
    {
        $validate = \Validator::make($this->request->all(),[
            'shop_name'=>'string|required',
            'user_name'=>'string|required',
            'register_name'=>'string|required',
            'email'=>'string|required|email',
            'phone_number'=>'string|required',
            'branch_id'=>'string|required',
        ]);

        if($validate->fails())
        {
            return $this->errorBadRequest($validate->messages()->toArray());
        }

        $shop_name = $this->request->get('shop_name');
        $user_name = $this->request->get('user_name');
        $register_name = $this->request->get('register_name');
        $email = $this->request->get('email');
        $phone = $this->request->get('phone_number');
        $branch = mongo_id('5e7c5db8321b00000f0005c5');
        $position = mongo_id('5e7c7bf7321b00000f0005cc');
        $dept = mongo_id('5e7c845a321b00000f0005d3');

        $shopUser = new Shop();
        $userExist = $shopUser->getUser($register_name);

        $attributes = [
            'shop_name'=>$shop_name,
            'user_name'=> $user_name,
            'register_name'=> $register_name,
            'email'=> $email,
            'phone_number'=> $phone,
            'branch_id'=>$branch
        ];

        if(empty($userExist))
        {
            $userValidator = \Validator::make($this->request->all(),[
                'email'=>'unique:user',
                'phone_number'=>'unique:user',
            ]);

            if($userValidator->fails())
            {
                return $this->errorBadRequest($userValidator->messages()->toArray());
            }

            $newshop = $this->shopRepository->create($attributes);

            $newUser = [
                'full_name'=>$register_name,
                'phone_number'=>$phone,
                'email'=>$email,
                'position_name'=>$position,
                'branch_name'=>$branch,
                'shop_name'=>mongo_id($newshop->_id),
                'dept_name'=>$dept,
            ];
            $temp = User::create($newUser);
            return $this->successRequest($newshop);
        }
        $shop = $this->shopRepository->create($attributes);
        return $this->successRequest($shop);
    }

    public function update()
    {
        $validator = \Validator::make($this->request->all(),[
            'register_name'=>'string|required',
            'email'=>'string|required',
            'phone_number'=>'string|required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $shopExist = $this->shopRepository->findByField('shop_name',$this->request->get('shop_name'))->first();

        if(!empty($shopExist))
        {
            $id = $shopExist->_id;
            $attributes = [
                'register_name'=>$this->request->get('register_name'),
                'email'=>$this->request->get('email'),
                'phone'=>$this->request->get('phone')
            ];

            $register_name = $this->request->get('register_name');
            $email = $this->request->get('email');
            $phone = $this->request->get('phone_number');
            $branch = mongo_id('5e7c5db8321b00000f0005c5');
            $position = mongo_id('5e7c7bf7321b00000f0005cc');
            $dept = mongo_id('5e7c845a321b00000f0005d3');

            $shopUser = new Shop();
            $userExist = $shopUser->getUser($register_name);

            if(empty($userExist))
            {
                $userValidator = \Validator::make($this->request->all(),[
                    'email'=>'unique:user',
                    'phone_number'=>'unique:user',
                ]);

                if($userValidator->fails())
                {
                    return $this->errorBadRequest($userValidator->messages()->toArray());
                }

                $newUser = [
                    'full_name'=>$register_name,
                    'phone_number'=>$phone,
                    'email'=>$email,
                    'position_name'=>$position,
                    'branch_name'=>$branch,
                    'shop_name'=>mongo_id($shopExist->_id),
                    'dept_name'=>$dept,
                ];
                $temp = User::create($newUser);
            }
            $shop = $this->shopRepository->update($attributes,$id);
            return $this->successRequest('Sửa thành công');
        }
    }

    public function delete()
    {
        $shopExist = $this->shopRepository->findByField('shop_name',$this->request->get('shop_name'))->first();
//        $branch = $shopExist->branch();
//        return $shopExist->branch();
        if(!empty($shopExist))
        {
            $shopExist->forceDelete();
            return $this->successRequest('Xóa thành công');
        }
        return $this->errorBadRequest('Shop không tồn tại');
    }
    public function list()
    {
        $validator = \Validator::make($this->request->all(),[
            'branch_id'=>'required'
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $is_detail = $this->request->get('is_detail');
        $params= [
            'is_detail' => $is_detail,
            'branch_id'=>$this->request->get('branch_id'),
        ];

        $temp = $this->shopRepository->getShop($params);
        return $temp;
    }
}