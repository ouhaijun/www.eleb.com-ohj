<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    //商家列表
    public function list(Request $request)
    {
        //获取所有商家信息
        $shops=Shop::where('shop_name','like',"%$request->keyword%")->get();
        $datas=[];
        foreach ($shops as $shop){
            $data=[
                "id"=>$shop->id,
                "shop_name"=>$shop->shop_name,
                "shop_img"=>$shop->shop_img,
                "shop_rating"=>$shop->shop_rating,
                "brand"=>$shop->brand,
                "on_time"=>$shop->on_time,
                "fengniao"=>$shop->fengniao,
                "bao"=>$shop->bao,
                "piao"=>$shop->piao,
                "zhun"=>$shop->zhun,
                "start_send"=>$shop->start_send,
                "send_cost"=>$shop->send_cost,
                "notice"=>$shop->notice,
                "discount"=>$shop->discount,
            ];
            $datas[]=$data;
        }
        return json_encode($datas);
    }

    public function like()
    {
        $id=$_GET['id'];


        $shop=Shop::where('id',$id)->first();
        $category=MenuCategory::where('shop_id',$shop->id)->get();

        foreach ($category as $v){
        $menu=Menu::where([['shop_id',$shop->id],['category_id',$v->id]])->get();
        foreach ($menu as $m){
            $menus[]=[
                'goods_id'=>$m->id,
                'goods_name'=>$m->goods_name,
                'rating'=>$m->rating,
                'goods_price'=>$m->goods_price,
                'description'=>$m->description,
                'month_sales'=>$m->month_sales,
                'rating_count'=>$m->rating_count,
                'tips'=>$m->tips,
                'satisfy_count'=>$m->satisfy_count,
                'satisfy_rate'=>$m->satisfy_rate,
                'goods_img'=>$m->goods_img,
            ];
        }
        $me=array_splice($menus,0);
        $value[]=[
            "description"=>$v->description,
            "is_selected"=>$v->is_selected,
            "name"=>$v->name,
            "type_accumulation"=>$v->type_accumulation,
            "goods_list"=>$me,
        ];

        }

            $data=[
                "id"=>$shop->id,
                "shop_name"=>$shop->shop_name,
                "shop_img"=>$shop->shop_img,
                "shop_rating"=>$shop->shop_rating,
                "service_code"=>4.6,
                "foods_code"=>4.4,
                "high_or_low"=>true,
                "h_l_percent"=>30,

                "brand"=>$shop->brand,
                "on_time"=>$shop->on_time,
                "fengniao"=>$shop->fengniao,
                "bao"=>$shop->bao,
                "piao"=>$shop->piao,
                "zhun"=>$shop->zhun,
                "start_send"=>$shop->start_send,
                "send_cost"=>$shop->send_cost,
                "distance"=>$shop->distance,
                "estimate_time"=>$shop->estimate_time,
                "notice"=>$shop->notice,
                "discount"=>$shop->discount,


                "evaluate"=> [[
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 1,
                "send_time"=> 30,
                "evaluate_details"=> "不怎么好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 4.5,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 5,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 4.7,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
            ],
            [
                "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 5,
                "send_time"=>30,
                "evaluate_details"=>"很好吃"
            ]
        ],
                "commodity"=> $value
            ];

                /*[[
                "description"=> "大家喜欢吃，才叫真好吃。",
                "is_selected"=> true,
                "name"=> "热销榜",
                "type_accumulation"=> "c1",
                "goods_list"=> [[
                    "goods_id"=> 100001,
                        "goods_name"=> "吮指原味鸡",
                        "rating"=> 4.67,
                        "goods_price"=> 11,
                        "description"=> "",
                        "month_sales"=> 590,
                        "rating_count"=> 91,
                        "tips"=> "具有神秘配方浓郁的香料所散发的绝佳风味，鲜嫩多汁。",
                        "satisfy_count"=> 8,
                        "satisfy_rate"=> 95,
                        "goods_img"=> "/images/slider-pic4.jpeg"
                    ],
                 [
                     "goods_id"=> 100003,
                        "goods_name"=> "蟹黄堡",
                        "rating"=> 5,
                        "goods_price"=> 17,
                        "description"=> "",
                        "month_sales"=> 723,
                        "rating_count"=> 65,
                        "tips"=> "浓郁汉堡酱，香脆鲜辣多汁",
                        "satisfy_count"=> 6,
                        "satisfy_rate"=> 100,
                        "goods_img"=> "/images/slider-pic4.jpeg"
                    ]


            ],
                    [
                        "description"=> "",
                "is_selected"=> false,
                "name"=> "美味汉堡",
                "type_accumulation"=> "c2",
                "goods_list"=> [[
                        "goods_id"=> 100004,
                        "goods_name"=> "麦香鸡腿堡",
                        "rating"=> 5,
                        "goods_price"=> 18,
                        "description"=> "",
                        "month_sales"=> 723,
                        "rating_count"=> 65,
                        "tips"=> "整块无骨鸡腿肉, 浓郁汉堡酱，香脆鲜辣多汁。",
                        "satisfy_count"=> 6,
                        "satisfy_rate"=> 100,
                        "goods_img"=> "/images/slider-pic4.jpeg"
                    ],
                    [
                        "goods_id"=> 100005,
                        "goods_name"=> "腿堡",
                        "rating"=> 5,
                        "goods_price"=> 18,
                        "description"=> "",
                        "month_sales"=> 723,
                        "rating_count"=> 65,
                        "tips"=> "整块无骨鸡腿肉, 浓郁汉堡酱，香脆鲜辣多汁。",
                        "satisfy_count"=> 6,
                        "satisfy_rate"=> 100,
                        "goods_img"=> "/images/slider-pic4.jpeg"
                    ]
                ]
            ]
]
                    ]
                ];*/






        return $data;


        
    }

}
