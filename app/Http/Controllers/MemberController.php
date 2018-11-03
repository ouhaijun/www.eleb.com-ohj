<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\SignatureHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Validator;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    //
    public function create(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'username'=>'required',
            'password'=>'required',
            'tel'=>'required',
            //'sms'=>'required',
        ],[
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
            'tel.required'=>'手机不能为空',
        ]);
        $sum=Redis::get('sum_'.$request->tel);
        //$tel=Redis::get('tel');
        //dd($sum);
        //验证验证码是否正确
        if($sum!=$request->sms){
            return [
                'status'=>"false",
                "message"=>'验证码错误',
            ];
        }

        if($validator->fails()){
            return [
                'error_code'=>-1,
                "message"=>$validator->errors(),

            ];
        }
        Member::create([
            'username'=>$request->username,
            'password'=>bcrypt($request->password),
            'tel'=>$request->tel,
            'rememberToken'=>str_random(50),
            'status'=>1,
        ]);

    return [
        'status'=>"true",
                "message"=>'注册成功',


];
    }
    //短信
    public function store($tel,$sum)
    {

        $params = array ();

        // *** 需用户填写部分 ***
        // fixme 必填：是否启用https
        $security = false;

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "LTAIqvGApnP2oAhs";
        $accessKeySecret = "9X6EIPm2fWgmq4nSGkOoRnZ9TiIAYa";

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = "$tel";

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = "一个分享";

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = "SMS_149097566";

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = Array (
            "code" => $sum,
            //"product" => "阿里通信"
        );

        // fixme 可选: 设置发送短信流水号
        $params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        $params['SmsUpExtendCode'] = "1234567";


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            )),
            $security
        );

         dd($content);

        
    }

    public function sms()
    {
        $tel=$_GET['tel'];
        $sum=mt_rand(100000,999999);
        //Redis::setex('tel',300,$tel);
        Redis::setex('sum_'.$tel,300,$sum);
        $this->store($tel,$sum);
    }




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
        if(Auth::attempt(['username'=>$request->name,'password'=>$request->password])){
            return [

                    "status"=>"true",
        "message"=>"登录成功",
        "user_id"=>Auth::user()->id,
        "username"=>Auth::user()->username,

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
    //修改密码
    public function update(Request $request)
    {
        /*$oldPassword=$request->oldPassword;
        $newPassword=$request->newPassword;*/
        $member=Member::where('id',Auth::user()->id)->first();
        if(!Hash::check($request->oldPassword,$member->password)){
            return [
                "status"=> "false",
      "message"=> "修改失败,请输入正确的旧密码"
    ];
        }

        Auth::user()->update([
            'password'=>bcrypt($request->newPassword),
        ]);
        return [
                "status"=> "true",
      "message"=> "修改成功"

        ];


    }
    //重置密码
    public function edit(Request $request)
    {
        //根据传过来的电话查询数据库
        $tel=Member::where('tel',$request->tel)->first();
        if($request->tel!=$tel->tel){
            return [
                "status"=> "false",
      "message"=> "添加失败,手机号错误"
            ];
    }
     //拿取redis的验证码
        $sum=Redis::get('sum_'.$request->tel);
        //$tel=Redis::get('tel');
        //dd($sum);
        //验证验证码是否正确
        if($sum!=$request->sms){
            return [
                'status'=>"false",
                "message"=>"验证码错误",
            ];
        }
        $tel->update([
            'password'=>bcrypt($request->password),
        ]);
        return [
            "status"=> "true",
      "message"=> "添加成功"
        ];


    }



}
