<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/module/_baseClass/BaseAction.php';
class B extends BaseAction{
	
	public function action(){


        $sql = "select * from #__productxiyu1 where (spec like '%CAS%' or name like '%CAS%' ) and brand = 'J&K' limit 0,1000";
        $dbAgent = DBAgent::getInstance();
        $result = $dbAgent->queryRecords($sql);
        $products = array();
        $i = 0;
        foreach ($result as $val) {
            $brandName = $val['brand'];
            $sql = "select b.brand_id as id from #__brand b where b.name='$brandName'";
            $dbAgent = DBAgent::getInstance();
            $brandId = $dbAgent->querySingleRecord($sql);
            if(empty($brandId)){
                $brandId['id'] = 0;
            }
            $casNo = "";
            $ename = "";
            $purity = "0";
            $flag = 0;
            $spec = json_decode($val['spec'], true);
            for($i = 0;$i<count($spec);$i++){
                if (array_key_exists("CASNo.",$spec)) {
                    $casNo = $spec['CASNo.'];
                }else{
                    $flag = 1;
                }
                if (array_key_exists("英文名称",$spec)) {
                    $ename = $spec['英文名称'];
                }
                if (array_key_exists("规格",$spec)) {
                    $purity  = $spec['规格'];
                }
                /*if (array_key_exists("包装",$spec)) {
                    $packing = $spec['包装'];
                }
                if (array_key_exists("储存条件",$spec)) {
                    $trade = $spec['储存条件'];
                }*/
            }
            if($flag == 1){
                continue;
            }
            $name = "";
            $pattern = '/[^\x00-\x80]/';
            if(strstr($val['name'],"，")){
                if(strstr($val['name'],"CAS")){
                    $name = explode("，",$val['name'])[1];
                    if(!preg_match($pattern,$name)){
                        $name = explode("，",$val['name'])[2];
                        if(!preg_match($pattern,$name)){
                            $name = explode("，",$val['name'])[3];
                            if(!preg_match($pattern,$name)){
                                $name = explode("，",$val['name'])[4];
                            }
                        }
                    }
                }else{
                    $name = explode("，",$val['name'])[0];
                    if(!preg_match($pattern,$name)){
                        $name = explode("，",$val['name'])[1];
                        if(!preg_match($pattern,$name)){
                            $name = explode("，",$val['name'])[2];
                            if(!preg_match($pattern,$name)){
                                $name = explode("，",$val['name'])[3];
                            }
                        }
                    }
                }
            }else{
                if(strstr($val['name'],"CAS")){
                    $name = explode(",",$val['name'])[1];
                    if(!preg_match($pattern,$name)){
                        $name = explode(",",$val['name'])[2];
                        if(!preg_match($pattern,$name)){
                            $name = explode(",",$val['name'])[3];
                            if(!preg_match($pattern,$name)){
                                $name = explode(",",$val['name'])[4];
                            }
                        }
                    }
                }else{
                    $name = explode(",",$val['name'])[0];
                    if(!preg_match($pattern,$name)){
                        $name = explode(",",$val['name'])[1];
                        if(!preg_match($pattern,$name)){
                            $name = explode(",",$val['name'])[2];
                            if(!preg_match($pattern,$name)){
                                $name = explode(",",$val['name'])[3];
                            }
                        }
                    }
                }
            }

            $product['sid'] = 28;
            $product['name'] = $name;
            $product['pinyin'] = '';//拼音
            $product['EnglishName'] = $ename;
            $product['alias'] = $name;//别名
            $product['purity'] = $purity;
            $goodsCode = explode(" ", $val['model'])[1];//货号
            $size = explode("-",$goodsCode)[1];
            $product['size'] = $size;//规格
            $product['code'] = '';//编号
            $product['goods_code'] = $goodsCode;
            $product['CASnumber'] = $casNo;
            $product['packing'] = "独立包装";
            $product['unit'] = "瓶";
            $product['images'] = "/images/temp_pc/goods.jpg";
            $product['fre_id'] = 0;
            $product['address_id'] = 0;
            $product['ptid'] = 120;
            $product['first_tid'] = 93;
            $product['second_tid'] = 115;
            $product['third_tid'] = 120;
            $product['forth_tid'] = 0;
            $product['fifth_tid'] = 0;
            $product['producer'] = "国产";//制造商
            $product['price'] = $val['price'];
            $product['weight'] = 0;//重量
            $product['volume'] = 0;//体积
            $product['sale_num'] = 0;
            $product['comment_num'] = 0;
            $product['intro'] = '';
            $product['view_num'] = 0;
            $product['time'] = time();
            $product['brand'] = $brandName;
            $product['brand_id'] = $brandId['id'];
            $product['other_info'] = $val['spec'];
            $product['intro_mobile'] = '';
            $product['video_url'] = '';
            $product['video_img'] = '';
            $product['v1_discount'] = 1;
            $product['v2_discount'] = 1;
            $product['v3_discount'] = 1;
            $product['v4_discount'] = 1;
            $product['can_testing'] = 0;
            $product['quality_testing'] = 0;
            $product['can_guarantee'] = 0;
            $product['guarantee_1'] = 0;
            $product['guarantee_2'] = 0;
            $product['guarantee_5'] = 0;
            $product['is_harmful'] = 0;
            $product['shelf_life'] = "三个月以上";
            $product['store'] = "常温保存";
            $product['traffic'] = "常温运输";
            $products[] = $product;
        }
        foreach ($products as $val) {
            $sql = "insert into #__product_temp_2_24(sid,name,pinyin,EnglishName,alias,purity,size,code,goods_code,CASnumber,
                  packing,unit,images,fre_id,address_id,ptid,first_tid,second_tid,third_tid,forth_tid,fifth_tid,producer,
                  price,weight,volume,sale_num,comment_num,intro,view_num,time,brand,brand_id,other_info,intro_mobile,video_url,
                  video_img,v1_discount,v2_discount,v3_discount,v4_discount,can_testing,quality_testing,can_guarantee,
                  guarantee_1,guarantee_2,guarantee_5,is_harmful,shelf_life,store,traffic,status)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
            $dbAgent->query($sql,array($val['sid'],$val['name'], $val['pinyin'],$val['EnglishName'],$val['alias'],$val['purity'],
                $val['size'],$val['code'],$val['goods_code'],$val['CASnumber'],$val['packing'],$val['unit'],$val['images'],$val['fre_id'],
                $val['address_id'],$val['ptid'],$val['first_tid'],$val['second_tid'],$val['third_tid'],$val['forth_tid'],$val['fifth_tid'],$val['producer'],
                $val['price'],$val['weight'],$val['volume'],$val['sale_num'],$val['comment_num'],$val['intro'],$val['view_num'],$val['time'],
                $val['brand'],$val['brand_id'],$val['other_info'],$val['intro_mobile'],$val['video_url'],$val['video_img'],$val['v1_discount'],$val['v2_discount'],
                $val['v3_discount'],$val['v4_discount'],$val['can_testing'],$val['quality_testing'],$val['can_guarantee'],$val['guarantee_1'],$val['guarantee_2'],$val['guarantee_5'],
                $val['is_harmful'],$val['shelf_life'],$val['store'],$val['traffic'],2));
        }
        $i = 1;
        if($i == 1){
            exit(0);
        }
        exit(0);
    }
}