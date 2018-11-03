<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Validator;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //添加
    public function store(Request $request)
    {
        $list=$request->goodsList;
        $count=$request->goodsCount;
        //dd($list);

        for($i=0;$i<count($list);$i++){
            if([['goods_id'==$list[$i]],['user_id',Auth::user()->id]]){

                Auth::user()->update([
                    'amount'=>$count[$i].'+'.'amount',
                ]);
            }
            Cart::create([
                'user_id'=>Auth::user()->id,
                'goods_id'=>$list[$i],
                'amount'=>$count[$i],
            ]);
        }

        /*dd($request);
        Redis::set('list',serialize($request->goodsList));
        Redis::set('count',serialize($request->goodsCount));*/
        return [
            "status"=> "true",
            "message"=> "添加成功",
        ];


    }
    public function index()
    {
        //根据当前用户id查询属于用户的数据
        $carts=Cart::where('user_id',Auth::user()->id)->get();

        foreach($carts as $cart) {
        //根据上面查询出来的goods_id查询商品
            $list = Menu::where('id', $cart->goods_id)->get();

            //dd($list);



            foreach ($list as $v) {
                $data[] = [
                    'goods_id' => $v->id,
                    'goods_name' => $v->goods_name,
                    'goods_img' => $v->goods_img,
                    'goods_price' => $v->goods_price,
                    'amount' => $cart->amount,
                ];
            }
        }
            $price = 0;
            foreach ($data as $v) {
                $price += $v['goods_price'] * $v['amount'];
            }
            $datas = [
                "goods_list" => $data,
                "totalCost" => $price,
            ];

        return $datas;
    }
}
