<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;

class AuthController extends Controller
{
    protected $request;

    protected $auth;

    public function __construct(
        Request $request,AuthManager $auth
    )
    {
        $this->auth=$auth;
        $this->request=$request;

        parent::__construct();
    }

    public function login()
    {
        $validator = \Validator::make($this->request->all(),[
            'shop_name'=>'string|required',
            'phone_number'=>'string|required',
        ]);

        if($validator->fails())
        {
            return $this->errorBadRequest($validator->messages()->toArray());
        }

        $credentials = $this->request->only('shop_name', 'phone_number');

        $credentials['shop_name'] = strtolower($credentials['shop_name']);
        if(!$token = $this->auth->attempt($credentials))
        {
            return $token;
        }

        $data = array('token'=>$token);

        $this->auth->setToken($token);
        $user = $this->auth->user();

        return $this->successRequest($data);
    }
}