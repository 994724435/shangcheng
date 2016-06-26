<?php

namespace Home\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class UserController extends CommonController {
		public function qq(){
	require_once("/Public/API/qqConnectAPI.php");
//  session_start();
//	require_once("/Public/API/comm/config.php");
//	require_once("/Public/API/class/QC.class.php");

//          vendor('API.qqConnectAPI');
			$qc = new QC();
            $qc->qq_login();
//			$qc = new QC();
//			$acs = $qc->qq_callback();
//			$oid = $qc->get_openid();
//			$qc = new QC($acs,$oid);
//			$uinfo = $qc->get_user_info();
//			print_r($uinfo);die;
	}

 //qq验证登陆
 public function qq_callback(){
    vendor('Connect.qqConnectAPI');
    $qc = new QC();
    $qc->qq_callback();
    $qc->get_openid();
    $this->success("QQ登陆成功",U('Login/qq_user'));
 }

	public function shopping_cart(){
		if(session('login_user')){
		$member = M('Member');
		$product = M('Product');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		if($user['vip_complete_order']){
			$complete = explode(';',$user['vip_complete_order']);
		}
		if($user['vip_notpay_order']){
			$notpay = explode(';',$user['vip_notpay_order']);
		}
		foreach($complete as $val){
			$comp_orders[] = explode('+',$val);
		}
		foreach($comp_orders as $key=>$val){
			$comp_pro[$key] = $product->where(array('pro_sku'=>$val['1']))->find();
			$comp_pro[$key]['pro_num'] = $val['2'];
		}
		foreach($notpay as $val){
			$pay_orders[] = explode('+',$val);
		}
		foreach($pay_orders as $key=>$val){
			$pay_pro[$key] = $product->where(array('pro_sku'=>$val['1']))->find();
			$pay_pro[$key]['pro_num'] = $val['2'];
			$pay_pro[$key]['pro_time'] = $val['3'];
		}
		$data = array(
			'pay_orders'=>$pay_pro,
			'comp_orders'=>$comp_pro,
			);
		// print_r($notpay);die;
		$this->assign($data);
		$this->display();	
		}else{
	                  echo "<script>alert('请登录')</script>";
                               echo "<script>window.history.go(-1);</script>";
		}


	}
	
	public function login(){
		$this->display();
	}
	public function index(){
		$member = M('Member');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		if($user['vip_notpay_order']){
			$notpay_order = explode(';',$user['vip_notpay_order']);
			$notpay_count = count($notpay_order);
		}else{
			$notpay_count = count($notpay_order);
		}
		$data = array(
			'not_count'=>$notpay_count
			);
		$this->assign($data);
		$this->display();
	}
	public function register(){
		if(IS_POST){
			$account = I('post.account');
			$password = I('post.password');
			$mail = I('post.mail');
        	$user = M('Member');
        	$save = array(
        		'vip_account'=>$account,
        		'vip_password'=>$password,
        		'vip_mail'=>$mail
        		);
        	$tmp = $user->where(array('vip_account'=>$account))->find();
        	if(false){
        		echo "<script>alert('账号已存在');window.history.go(-1);</script>";
        	}else{
        		$date = $user->where(array('vip_mail'=>$mail))->find();
        		if($date){
        			echo "<script>alert('该邮箱已注册');window.history.go(-1);</script>";
        		}else{
        			$res = $user->add($save);
	        		if($res){
	        			session('login_id',$res);
	        			
	        			session('login_state',1);
	        			 session('login_user',$account);
		        		$this->display('succeed');


		        	}
        		}
        	}
		}
		$this->display();
	}

	function login_ajax(){
		$username = $_POST['account'];
		$password = $_POST['password'];
		$member = M('Member');
		$user = $member->where(array('vip_account'=>$username))->find();
		if(!$user){
			$user = $member->where(array('vip_username'=>$username))->find();
			if(!$user){
				$user = $member->where(array('vip_mail'=>$username))->find();
				if(!$user){
					$res['res'] = 1;
				}
			}
		}
		if(!$res){
			if($password == $user['vip_password']){
				$res['res'] = 2;
			}else{
				$res['res'] = 0;
			}
		}
		$res['login_state'] = session('login_state',1);
		$res['login_user'] = session('login_user',$user['vip_mail']);
		if(!$res['login_user']){
			$res['login_user'] = session('login_user',$user['vip_account']);
			if(!$res['login_user']){
				$res['login_user'] = session('login_user',$user['vip_username']);
			}
		}
		$res['login_id'] = session('login_id',$user['vip_id']);
		echo json_encode($res);
	}


	public function logout(){
		session(null);
		echo "<script>window.location.href='/index.php/Home/User/login';</script>";
	}

	public function address(){
		$member = M('Member');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		if(IS_POST){
			if($user['vip_address']){
				$address = $user['vip_address'].'/'.I('post.name').'-'.I('post.address').'-'.I('post.tel').'-'.I('post.zip_code').'-0';
			}else{
				$address = I('post.name').'-'.I('post.address').'-'.I('post.tel').'-'.I('post.zip_code').'-1';
			}
			$tmp = $member->where(array('vip_id'=>session('login_id')))->save(array('vip_address'=>$address));
			if($tmp){
				echo "<script>window.location.href='/index.php/Home/User/address';</script>";
			}else{
				echo "<script>alert('添加失败');window.history.go(-1);</script>";
			}
		}
		if($user['vip_address']){
			$address = explode('/',$user['vip_address']);
			foreach($address as $val){
				$res[] = explode('-',$val);
			}
			$num = count($res);
		}else{
			$num = 0;
		}
		// print_r($num);die;
		$data = array(
			'address_num'=>$num,
			'address'=>$res
			);
		$this->assign($data);
		$this->display();
	}

	function addel(){
		$member = M('Member');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		$address = explode('/',$user['vip_address']);
		foreach($address as $val){
			$res[] = explode('-',$val);
		}
		$add = $_POST['address'];
		$asd = explode('-',$add);
		$abc = array_search($asd,$res);
		unset($res[$abc]);
		foreach($res as $val){
			$tmp[] = implode('-',$val);
		}
		$a = implode('/',$tmp);
		$b = $member->where(array('vip_id'=>session('login_id')))->save(array('vip_address'=>$a));
		echo json_encode($b);
	}

	public function my_order(){
	   if(session('login_user')){
		$member = M('Member');
		$product = M('Product');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		if($user['vip_success_order']){
			$complete = explode(';',$user['vip_success_order']);
		}
		// if($user['vip_notpay_order']){
		// 	$notpay = explode(';',$user['vip_notpay_order']);
		// }
		foreach($complete as $val){
			$comp_orders[] = explode('+',$val);
		}
		foreach($comp_orders as $key=>$val){
			$val['1']  = substr($val['1'], 0,16);
			$comp_pro[$key] = $product->where(array('pro_sku'=>$val['1']))->find();
			$comp_pro[$key]['pro_num'] = $val['2'];
		}
		
//		print_r($comp_pro);die;
		foreach($notpay as $val){
			$pay_orders[] = explode('+',$val);
		}
		foreach($pay_orders as $key=>$val){
			$pay_pro[$key] = $product->where(array('pro_sku'=>$val['1']))->find();
			$pay_pro[$key]['pro_num'] = $val['2'];
			$pay_pro[$key]['pro_time'] = $val['3'];
		}
		$data = array(
			// 'pay_orders'=>$pay_pro,
			'comp_orders'=>$comp_pro,
			);
		// print_r($notpay);die;
		$this->assign($data);
		$this->display();
	   }	else{
	   	            echo "<script>alert('请登录')</script>";
                                   echo "<script>window.history.go(-1);</script>";
	   }

	}

	public function delete(){
		$sku = $_GET['sku'];
		$member = M('Member');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		$notpay_order = explode(';',$user['vip_notpay_order']);
		unset($notpay_order[$sku]);
		$arr = array('vip_notpay_order'=>implode(';',$notpay_order));
		$res = $member->where(array('vip_id'=>session('login_id')))->save($arr);
		if($res){
			echo "<script>window.location.href='/index.php/Home/User/shopping_cart';</script>";
		}
	}


	function ress(){
		$temp = $_POST['temp'];
		$member = M('Member');
		if(session('login_id')){
			$user = $member->where(array('vip_id'=>session('login_id')))->find();
			if($user['vip_address']){
				$arr = array('vip_address'=>$user['vip_address'].'/'.$temp);
			}else{
				$arr = array('vip_address'=>$temp);
			}
			$res = $member->where(array('vip_id'=>session('login_id')))->save($arr);
		}else{
			$user = $member->where(array('vip_id'=>1))->find();
			$arr = array('vip_address'=>$user['vip_address'].$temp);
			$res = $member->where(array('vip_id'=>1))->save($arr);
		}
		echo json_encode($res);
	}



//	public function shopping_cart(){
//		if(session('login_id')){
//			$product = M('Product');
//			$username = M('Member');
//			$member = $username->where(array('vip_id'=>session('login_id')))->find();
//			$shopping_cart = explode(';',$member['vip_shopping_cart']);
//			$member['vip_shopping_count'] = count($shopping_cart);
//			foreach ($shopping_cart as $key => $val){
//				$shopping_cart[$key] = explode('-',$val);
//			}
//			foreach ($shopping_cart as $key => $val){
//				$cart[] = $product->where(array('pro_sku'=>$val[1]))->find(); 
//			}
//			$this->assign(array('cart'=>$cart));
//			$this->display();
//		}else{
//			$this->display('login');
//		}
//		
//	}



	public function member_center(){
		if(IS_POST){
		        $user = I('post.user');
				$sex = I('post.sex');
				$birthday = I('post.birthday');
				$tel = I('post.tel');
				$member = M('Member');
				$arr = array(
					'vip_username'=>$user,
					'vip_sex'=>$sex,
					'vip_birthday'=>$birthday,
					'vip_tel'=>$tel,
					);
				$res = $member->where(array('vip_id'=>session('login_id')))->save($arr);			
			
			
			$upload = new \Think\Upload();// 实例化上传类
		    $upload->maxSize   =     3145728 ;// 设置附件上传大小
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     THINK_PATH; // 设置附件上传根目录
		    $upload->savePath  =     '../Public/Uploads/'; // 设置附件上传（子）目录
		    // 上传文件 
		    $info   =   $upload->upload();
		    if(!$info) {// 上传错误提示错误信息
		        $this->error($upload->getError());
		    }else{// 上传成功
		    	$path = ltrim($info['file0']['savepath'],'.');
		    	$src = $path.$info['file0']['savename'];
		        $user = I('post.user');
				$sex = I('post.sex');
				$birthday = I('post.birthday');
				$member = M('Member');
				$arr = array(
					'vip_username'=>$user,
					'vip_sex'=>$sex,
					'vip_birthday'=>$birthday,
					'vip_avatar'=>$src,
					);
				$res = $member->where(array('vip_id'=>session('login_id')))->save($arr);
				if($res){
					echo "<script>window.location.href='/index.php/Home/User/index'</script>";
				}else{
					echo "<script>alert('更新失败');window.history.go(-1);</script>";
				}
		    }
		}
		$this->display();
	}

	public function comment(){
		$member = M('Member');
		$comment = M('Procomment');
		$product = M('Product');
		$user = $member->where(array('vip_id'=>session('login_id')))->find();
		$ment = $comment->where(array('cmt_user'=>$user['vip_username']))->select();
		foreach($ment as $val){
			$pro[] = $product->where(array('pro_id'=>$val['cmt_pro_id']))->select();
		}
		foreach($ment as $key=>$val){
			foreach($pro as $k=>$v){
				$ment[$key]['cmt_pro_thumb'] = $pro[$key][0]['pro_thumb'];
			}
		}
		$data = array(
			'ment'=>$ment
			);
		// print_r($pro);
		// print_r($ment);die;
		$this->assign($data);
		$this->display();
	}

}