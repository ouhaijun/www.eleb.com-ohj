<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //添加订单
    public function store(Request $request)
    {
        //return 1;
        $validator=Validator::make($request->all(),[
            'address_id'=>'required',
        ],[
            'address_id.required'=>'地址id不能为空',
        ]);
        if($validator->fails()){
            return [
                'error_code'=>-1,
                "message"=>$validator->errors(),
            ];
        }
        //dd($request->address_id);
        //查询购物车最后一条数据
        /*$goods = Cart::select('goods_id', 'id')->where('user_id', '=', 1)->orderby('id', 'desc')->limit(1)->get();
        dd($goods);*/
        $cart=Cart::where('user_id',Auth::user()->id)->orderby('id','desc')->first();
       // dd($cart->goods_id);
        //根据购物车的goods_id查商品表
        $shop=Menu::find($cart->goods_id);
        //dd($shop->shop_id);
        //根据传过来的address_id查询地址表
        $address=Address::find($request->address_id);
        //dd($address->id);
        $count=Cart::where('user_id',Auth::user()->id)->get();

        $total=0;
        foreach($count as $v){
            $sum_id=$v->goods_id;
            $sum=Menu::find($sum_id);
            $total+=$v->amount*$sum->goods_price;
        }
        //dd($total);
        //开启事务
        DB::beginTransaction();//开始事务
        try{

            $order=Order::create([
                'user_id'=>Auth::user()->id,
                'shop_id'=>$shop->shop_id,
                'sn'=>rand(1000,9999),
                'province'=>$address->province,
                'city'=>$address->city,
                'county'=>$address->county,
                'address'=>$address->address,
                'tel'=>$address->tel,
                'name'=>$address->name,
                'total'=>$total,
                'status'=>0,
                'out_trade_no'=>rand(1000,9999),
            ]);
            //dd($order);
                $carts=Cart::where('user_id',Auth::user()->id)->get();
            //dd($carts);
            foreach($carts as $c){

                $shops=Menu::find($c->goods_id);
                //dd($shops);
                    OrderDetail::create([
                        'order_id'=>$order->id,
                        'goods_id'=>$c->goods_id,
                        'amount'=>$c->amount,
                        'goods_name'=>$shops->goods_name,
                        'goods_img'=>$shops->goods_img,
                        'goods_price'=>$shops->goods_price,
                    ]);
                $c->delete();

            }
            DB::commit();//提交事务
            //dd('ok');
        }catch (\Exception $e){
            DB::rollBack();
            //dd($e);
        }
        //dd($order);
        return [
            "status"=> "true",
            "message"=> "添加成功",
            "order_id"=>$order->id,
        ];

    }
    //订单列表
    public function index()
    {
        $order=Order::where('user_id',Auth::user()->id)->get();
        //dd($order->id);
        foreach($order as $v){
            $order_det=OrderDetail::where('order_id',$v->id)->get();

            foreach ($order_det as $o){
                $goods[]=[
                    'goods_id'=>$o->goods_id,
                    'goods_name'=>$o->goods_name,
                    'goods_img'=>$o->goods_img,
                    'amount'=>$o->amount,
                    'goods_price'=>$o->goods_price,
                ];


            }
            //dd($order_det->goods_name);
            $date[]=[
                'id'=>$v->id,
                'order_code'=>$v->sn,
                'order_birth_time'=>$v->created_at->format('Y-m-d H:i:s'),
                'order_status'=>$v->status,
                'shop_id'=>$v->shop_id,
                'shop_name'=>$v->shop->shop_name,
                'shop_img'=>$v->shop->shop_img,
                'goods_list'=>$goods,
                'order_price'=>$v->total,
                'order_address'=>$v->address,
            ];
            //dd($date);
        }
        return $date;

    }
    //订单详情
    public function show(Request $request)
    {
        //dd($request->id);

       // $id=$_GET['id'];
        $order=Order::where('id',$request->id)->get();
        //dd($order->id);
        foreach($order as $v){
            $order_det=OrderDetail::where('order_id',$v->id)->get();

            foreach ($order_det as $o){
                $goods[]=[
                    'goods_id'=>$o->goods_id,
                    'goods_name'=>$o->goods_name,
                    'goods_img'=>$o->goods_img,
                    'amount'=>$o->amount,
                    'goods_price'=>$o->goods_price,
                ];


            }
            //dd($order_det->goods_name);
            $date=[
                'id'=>$v->id,
                'order_code'=>$v->sn,
                'order_birth_time'=>$v->created_at->format('Y-m-d H:i:s'),
                'order_status'=>"代付款",
                'shop_id'=>$v->shop_id,
                'shop_name'=>$v->shop->shop_name,
                'shop_img'=>$v->shop->shop_img,
                'goods_list'=>$goods,
                'order_price'=>$v->total,
                'order_address'=>$v->address,
            ];
            //dd($date);
        }
        return $date;

        
    }
}
