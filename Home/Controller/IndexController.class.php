<?php

namespace Home\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class IndexController extends CommonController {

    public function index(){
        $procate = M('Procate');
        $product = M('Product');
        $banner = M('Banner');
        $recommend = M('Recommend');
        $pcate = $procate->select();
        $cate = array();
        foreach($pcate as $value){
            if($value['cate_pid']==0){
                $cate[]=$value;
            }
            foreach($cate as $key=>$val){
                if($val['cate_id']==$value['cate_pid']){
                    $cate[$key]['cate_son'][]=$value;
                }
                foreach($val['cate_son'] as $k=>$v){
                    if($v['cate_id']==$value['cate_pid']){
                        $cate[$key]['cate_son'][$k]['cate_son'][]=$value;
                    }
                }
            }
        }
        foreach($cate as $key=>$value){
            $pro_cate[$key][] = $value['cate_id'];
            foreach($value['cate_son'] as $val){
                $pro_cate[$key][] = $val['cate_id'];
                foreach($val['cate_son'] as $v){
                    $pro_cate[$key][] = $v['cate_id'];
                }
            }
        }
        $cate_id1 = $pro_cate[0];
        $cate_id2 = $pro_cate[1];
        $cate_id3 = $pro_cate[2];
        $pro1 = $product->where(array('pro_classify_id'=>array('in',$cate_id1)))->limit(8)->select();
        $pro2 = $product->where(array('pro_classify_id'=>array('in',$cate_id2)))->limit(8)->select();
        $pro3 = $product->where(array('pro_classify_id'=>array('in',$cate_id3)))->limit(8)->select();
        $ptj1 = $product->where(array('pro_classify_id'=>array('in',$cate_id1)))->order('pro_deal DESC')->limit(1)->find();
        $ptj2 = $product->where(array('pro_classify_id'=>array('in',$cate_id2)))->order('pro_deal DESC')->limit(1)->find();
        $ptj3 = $product->where(array('pro_classify_id'=>array('in',$cate_id3)))->order('pro_deal DESC')->limit(1)->find();
        $reco = $recommend->select(); 
        $ban1 = $banner->where(array('ban_pageid'=>1))->select();
        $ban2 = $banner->where(array('ban_pageid'=>2))->select();
        // print_r($ptj1);die;
        $data=array(
            'pro1'=>$pro1,
            'pro2'=>$pro2,
            'pro3'=>$pro3,
            'ptj1'=>$ptj1,
            'ptj2'=>$ptj2,
            'ptj3'=>$ptj3,
            'cate'=>$cate,
            'reco'=>$reco,
            'ban1'=>$ban1,
            'ban2'=>$ban2,
            );
        $this->assign($data);
        $this->display();
    }

    public function search_product(){
        $keywords = $_GET['search'];
        $order = $_GET['order'];
        $product = M('Product');
        $procate = M('Procate');
        $pcate = $procate->where(array('cate_name'=>array('like',"%$keywords%")))->find();
        $pro = $product->where(array('pro_title'=>array('like',"%$keywords%")))->select();
        $current = I('get.page',1);
        $limit = 60;
        $art = ($current-1)*$limit;
        $url = get_url();//获取当前页面的URL地址
        $fir = strpos($url,'page');
        if($fir){
            $purl = mb_substr($url,0,$fir-1);
        }else{
            $purl = $url;
        }
        if($pcate){
            $pcat = $procate->where(array('cate_pid'=>$pcate['cate_id']))->select();
            if(!empty($pcat)){
                foreach($pcat as $val){
                    $procate_id[] = $val['cate_id'];
                }
                $pro_list = $product->where(array('pro_classify_id'=>array('in',$procate_id)))->limit($art,$limit)->select();
                if(!$pro_list){
                    $this->display('none');die;
                }
            }else{
                $pro_list = $product->where(array('pro_classify_id'=>$pcate['cate_id']))->limit($art,$limit)->select();
                $procate_id = $pcate['cate_id'];
                if(!$pro_list){
                    $this->display('none');die;
                }
            }
        }else if($pro){
            foreach($pro as $value){
                $procate_id = $value['pro_classify_id'];
            }
            $pro_list = $product->where(array('pro_classify_id'=>array('in',$procate_id)))->limit($art,$limit)->select();
        }else{
            $this->display('none');die;
        }
        $count = count($product->where(array('pro_classify_id'=>array('in',$procate_id)))->select());
        $show = list_page($current,$limit,$count,$purl);
        $page_num = ceil($count/$limit);
        $data = array(
            'pro_list'=>$pro_list,
            'show'=>$show,
            'page_num'=>$page_num,
            'key'=>$pcate['cate_name'],
            'count'=>$count,
            );
        // print_r($pro_list);die;
        $this->assign($data);
        $this->display();
    }

    public function product_detail(){
        $pro_sku = $_GET['pro_sku'];
        $product = M('Product');
        $procomment = M('Procomment');
        $pro = $product->where(array('pro_sku'=>$pro_sku))->find();
        $comment = $procomment->where(array('cmt_pro_id'=>$pro['pro_id']))->select();
        $cmt_count = $procomment->where(array('cmt_pro_id'=>$pro['pro_id']))->count();
        if($pro['pro_photo1']){
            $pro['pro_picture'][] = $pro['pro_photo1'];
        }
        if($pro['pro_photo2']){
            $pro['pro_picture'][] = $pro['pro_photo2'];
        }
        if($pro['pro_photo3']){
            $pro['pro_picture'][] = $pro['pro_photo3'];
        }
        if($pro['pro_photo4']){
            $pro['pro_picture'][] = $pro['pro_photo4'];
        }
        if($pro['pro_photo5']){
            $pro['pro_picture'][] = $pro['pro_photo5'];
        }
        $pro['pro_attribute'] = explode('/',$pro['pro_attribute']);
        foreach($pro['pro_attribute'] as $key=>$value){
            $arr[] = explode('-',$value);
            $pro['pro_attribute'] = $arr;
        }
        foreach($pro['pro_attribute'] as $key=>$value){
            $pr[] = $value[0];
        }
        $pro['pro_attribute'] = array_combine($pr,$pro['pro_attribute']);
        foreach($pro['pro_attribute'] as $key=>$val){
            $p[] = array_shift($val);
            $pro['pro_attribute'][$key] = $val;
        }
        $hot_pro = $product->where(array('pro_classify_id'=>$pro['pro_classify_id']))->order('pro_deal DESC')->limit(5)->select();
        $like = $product->where("pro_classify_id",$pro['pro_classify_id'])->order('pro_deal DESC')->limit(2)->select();
        $data = array(
            'pro'=>$pro,
            'hot'=>$hot_pro,
            'like'=>$like,
            'comment'=>$comment,
            'cmt_count'=>$cmt_count
            );
        $this->assign($data);
        $this->display();
    }

    public function pro_shopping_cart(){
        if(session('login_id')){
                     $sku = $_GET['sku'];
                    $num = $_GET['num'];
                    $product = M('Product');
                    $member = M('Member');
                    $pro = $product->where(array('pro_sku'=>$sku))->find();
                    $user = $member->where(array('vip_id'=>session('login_id')))->find();
                    if($user['vip_notpay_order']){
                        $notpay = $user['vip_notpay_order'].';'.$pro['pro_title'].'+'.$sku.'+'.$num;
                    }else{
                        $notpay = $pro['pro_title'].'+'.$sku.'+'.$num;
                    }
                    $res = $member->where(array('vip_id'=>session('login_id')))->save(array('vip_notpay_order'=>$notpay));
                    if($res){     
                        // echo "<script>window.history.go(-1);</script>";
                        echo "<script>window.location.href='/index.php/Home/User/shopping_cart';</script>";
                    }
        }else{
                            echo "<script>alert('请登录')</script>";
                            echo "<script>window.history.go(-1);</script>";
        }

    }

    public function orders(){
        if(session('login_id')){
            $product = M('Product');
            $pro_sku = $_GET['sku'];
            $num = $_GET['num'];
            $pro = $product->where(array('pro_sku'=>$pro_sku))->find();
            $data = array(
                    'pro'=>$pro,
                    'num'=>$num
                );
        }else{
            // session('login_state',1);
            // session('login_id',1);
            // session('vip',0);
            // $product = M('Product');
            // $pro_sku = $_GET['sku'];
            // $num = $_GET['num'];
            // $pro = $product->where(array('pro_sku'=>$pro_sku))->find();
            // $data = array(
            //     'pro'=>$pro,
            //     'num'=>$num
            echo "<script>alert('请登录')</script>";
            echo "<script>window.history.go(-1);</script>";           
            // );
        }
        // print_r($data);die;
        $this->assign($data);
        $this->display();
    }
    public function payment(){
        $product = M('Product');
        $member = M('Member');
        $pro_sku = $_GET['sku'];
        $num = $_GET['num'];
        $pro = $product->where(array('pro_sku'=>$pro_sku))->find();
        $user = $member->where(array('vip_id'=>session('login_id')))->find();
        // print_r($user);die;
        $notpay = $user['vip_notpay_order'];
        if($notpay){
            $new_order = $notpay.';'.$pro['pro_title'].'+'.$pro['pro_sku'].'+'.$num.'+'.time();
        }else{
            $new_order = $pro['pro_title'].'+'.$pro['pro_sku'].'+'.$num.'+'.time();
        }
        $res = $member->where(array('vip_id'=>session('login_id')))->save(array('vip_notpay_order'=>$new_order));
        if($res){
            $pro['pro_sku'] =$pro['pro_sku'].rand(100,999);    //添加四位唯一订单号
            $this->assign(array('pro'=>$pro,'num'=>$num));
            $this->display();
        }else{
            echo "<script>alert('请登录填写地址')</script>";
             echo "<script>window.history.go(-1);</script>";
        }
    }

    public function none(){
        $error = $_GET['search'];
        print_r($error);die;
        $this->assign('error',$error);
        $this->display();
    }

    public function pay_complete(){
        $address = $_GET['receive_address'];
        $mobile = $_GET['receive_mobile'];
        $name = $_GET['receive_name'];
        $zip = $_GET['receive_zip'];
        $num = $_GET['quantity'];           //数量
        $trade_no = $_GET['trade_no'];   //订单号
        $sku = $_GET['subject'];               // 商品ID
//      $sku =substr($sku, 0,16);
        $arr = array(
            'shop_name'=>$name,
            'shop_address'=>$address,
            'shop_mobile'=>$mobile,
            'shop_zip'=>$zip,
            'shop_num'=>$num,
            'shop_sku'=>$sku,
            'shop_state'=>'0'
            );
        $shop = M('Shop');
        $product = M('Product');
//		$menmber =M('Member');
        $pro = $product->where(array('pro_sku'=>$sku))->find();
        $title=$pro['pro_title'];



        $product = M('Product');
        $member = M('Member');
        $pro = $product->where(array('pro_sku'=>$sku))->find();
        $user = $member->where(array('vip_id'=>session('login_id')))->find();
        if($user['vip_success_order']){
                        $notpay = $user['vip_success_order'].';'.$pro['pro_title'].'+'.$sku.'+'.$num;
              }else{
                      $notpay = $pro['pro_title'].'+'.$sku.'+'.$num;
                    }
        $res2 = $member->where(array('vip_id'=>session('login_id')))->save(array('vip_success_order'=>$notpay));
		echo $res2;

		
        $shop->add($arr);
        if(!session('vip')){
            $member = M('Member');
            $member->where(array('vip_id'=>1))->save(array('vip_address'=>''));
        }else{
            $member = M('Member');
            $user = $member->where(array('vip_id'=>session('login_id')))->find();
            $success = $user['vip_success_order'];
            if($success){
                $new_order = $success.';'. $pro['title'].'+'.$pro['pro_sku'].'+'.$num.'+'.time().'+0+0';
            }else{
                $new_order = $pro['pro_title'].'+'.$pro['pro_sku'].'+'.$num.'+'.time().'+0+0';
            }
            $arr = array('vip_success_order'=>$new_order);
            $member->where(array('vip_id'=>session('login_id')))->save($new_order);
        }
        $this->assign('trade_no',$trade_no);
//		echo "<script>window.location.href='/index.php/Home/User/shopping_cart';</script>";
        $this->display();
// 		location.href="/index.php/Home/Index/pro_shopping_cart.html?sku="+sku+"&num="+num;
    }

}