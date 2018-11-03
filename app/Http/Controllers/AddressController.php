<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    //添加
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'provence'=>'required',
            'city'=>'required',
            'area'=>'required',
            'detail_address'=>'required',
            'tel'=>'required',
        ],[
            'provence.required'=>'省不能为空',
            'city.required'=>'市不能为空',
            'area.required'=>'区不能为空',
            'detail_address.required'=>'详细地址不能为空',
            'tel.required'=>'电话不能为空',
        ]);
        if($validator->fails()){
            return [
                'error_code'=>-1,
                "message"=>$validator->errors(),
            ];
        }
        Address::create([
            'province'=>$request->provence,
            'city'=>$request->city,
            'county'=>$request->area,
            'address'=>$request->detail_address,
            'tel'=>$request->tel,
            'name'=>$request->name,
            'user_id'=>Auth::user()->id,
            'is_default'=>1,
        ]);
        return [
            "status"=>'true',
            "message"=>'添加成功',
        ];

    }
    //用户地址列表
    public function index()
    {
        $addresss=Address::where('user_id',Auth::user()->id)->get();
        $datas=[];
        foreach ($addresss as $address){
            $data=[
                'provence'=>$address->province,
                'city'=>$address->city,
                'area'=>$address->county,
                'detail_address'=>$address->address,
                'tel'=>$address->tel,
                'name'=>$address->name,
                'id'=>$address->id,
            ];
            $datas[]=$data;
        }
        return $datas;
    }

    //修改
    public function edit()
    {
        $addresss=Address::all();
        foreach ($addresss as $address){
            $data=[
                'provence'=>$address->province,
                'city'=>$address->city,
                'area'=>$address->county,
                'detail_address'=>$address->address,
                'tel'=>$address->tel,
                'name'=>$address->name,
                'id'=>$address->id,
            ];
        }
        return $data;

    }
    //保存
    public function update(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'provence'=>'required',
            'city'=>'required',
            'area'=>'required',
            'detail_address'=>'required',
            'tel'=>'required',
        ],[
            'provence.required'=>'省不能为空',
            'city.required'=>'市不能为空',
            'area.required'=>'区不能为空',
            'detail_address.required'=>'详细地址不能为空',
            'tel.required'=>'电话不能为空',
        ]);
        if($validator->fails()){
            return [
                'error_code'=>-1,
                "message"=>$validator->errors(),
            ];
        }
        Address::where('id',$request->id)->update([
            'province'=>$request->provence,
            'city'=>$request->city,
            'county'=>$request->area,
            'address'=>$request->detail_address,
            'tel'=>$request->tel,
            'name'=>$request->name,
            //'user_id'=>Auth::user()->id,
            //'is_default'=>1,
        ]);
        return [
            "status"=>'true',
            "message"=>'修改成功',
        ];


    }

}
