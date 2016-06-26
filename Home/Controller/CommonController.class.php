<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

	public function _initialize(){
		if(session('login_state')){
			$user = session('login_user');
			$username = M('Member');
			$member = $username->where(array('vip_id'=>session('login_id')))->find();
			if($member['vip_notpay_order']){
				$shopping_cart = explode(';',$member['vip_notpay_order']);
//				print_r($shopping_cart);
				$member['vip_shopping_count'] = count($shopping_cart);
//				print_r($member);die;
			}else{
				$member['vip_shopping_count'] = 0;
			}
			$error = $_GET['search'];
			$add = explode('/',$member['vip_address']);
			foreach($add as $val){
				$address[] = explode('-',$val);
			}
			$product = M('Product');
			$hot = $product->order('pro_deal DESC')->limit(5)->select();
//			 print_r($member);die;
			$data = array(
				'user'=>$user,
				'member'=>$member,
				'error'=>$error,
				'address'=>$address,
				'hot'=>$hot
				);
//			$this->assign($data);	
			$this->assign($data);
		}
	}

}