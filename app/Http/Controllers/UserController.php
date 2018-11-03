<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    //登录
    public function login(Request $request)
    {
        //验证数据
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'password'=>'required',
        ],[
            'name.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
        ]);
        //验证失败
        if($validator->fails()){
            return [
                'error_code'=>-2,
                "message"=>$validator->errors(),

            ];
        }
      //登录验证
        if(Auth::attempt(['name'=>$request->name,'password'=>$request->password])){
            return [
              "error_code"=>0,
              "message"=>"登陆成功",
            ];
        }
        return[
            "error_code"=>-1,
            "message"=>[
                'name'=>[
                    '用户名或密码错误,请重新输入'
                ]
            ],
        ];
    }


}
