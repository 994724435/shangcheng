<?php

namespace Admin\Controller;
use Think\Controller;
class ProductController extends CommonController {
    public function index(){
        if($_POST['product_id']){
//          print_r($_POST['product_id']);
			$product=M('Product');		
			$res 	=$product->where(array('pro_id'=>$_POST['product_id']))->select();
			$result1=explode('/', $res[0]['pro_attribute']);
			for($i=0;$i<count($result1);$i++){
				$attrbute_data[]=explode('-', $result1[$i]); 
			}
			$attrbute_data=array_filter($attrbute_data);			
			$this->assign($attrbute_data);
//			print_r($attrbute_data);die;
        }
    	$cid=I('get.cid',0);//一级分类id
        $sid=I('get.sid',0);//二级分类ID
        $q=I('get.q','');//搜索
    	$product=M('Product');
    	$procate=M('Procate');
    	$cate = $procate->where(array('cate_id'=>$cid))->find();
    	$ccate = $procate->where(array('cate_id'=>$sid))->find();

    	$acate=$procate->where(array('cate_pid'=>0))->select();//一级分类
        $scate = $procate->where(array('cate_pid'=>$cid))->select();//二级分类
        $current = I('get.page',1);
        $limit = 10;	
        $art = ($current-1)*$limit;
        $url = get_url();//获取当前页面的URL地址
        $fir = strpos($url,'page');
        if($fir){
            $purl = mb_substr($url,0,$fir-1);
        }else{
            $purl = $url;
        }

        //数据总数
        if($q){
    		$count=$product->where(array('pro_name'=>array('like',"%$q%")))->count();
    	}else{
            if($sid){
                $count=$product->where(array('pro_classify_id'=>$sid))->count();
            }else{
                if($cid){
                    $ids=$procate->field('cate_id')->where(array('cate_pid'=>$cid))->select();
                    $idr=array(0=>$cid);
                    foreach($ids as $value){
                        $idr[]=$value['cate_id'];
                    }
                    $count=$product->where(array('pro_classify_id'=>array('IN',$idr)))->count();
                }else{
                    $count=$product->count();
                    
                }
            }
    	}
    	$show = list_page($current,$limit,$count,$purl);

        //数据
        if($q){
            $news=$product->join('`ast_procate` ON ast_product.pro_classify_id = ast_procate.cate_id')->where(array('pro_name'=>array('like',"%$q%")))->limit($art,$limit)->select();
        }else{
            if($sid){
                $news=$product->join('`ast_procate` ON ast_product.pro_classify_id = ast_procate.cate_id')->where(array('pro_classify_id'=>$sid))->limit($art,$limit)->select();
            }else{
                if($cid){
                    $ids=$procate->field('cate_id')->where(array('cate_pid'=>$cid))->select();
                    $idr=array(0=>$cid);
                    foreach($ids as $value){
                        $idr[]=$value['cate_id'];
                    }
                    $news=$product->join('`ast_procate` ON ast_product.pro_classify_id = ast_procate.cate_id')->where(array('pro_classify_id'=>array('IN',$idr)))->limit($art,$limit)->select();
                }else{
                    $news=$product->join('`ast_procate` ON ast_product.pro_classify_id = ast_procate.cate_id')->limit($art,$limit)->select();
                }
            }
        }
        $comment = M('Procomment');
        $ment = $comment->select();
        foreach($news as $key=>$val){
            foreach($ment as $k=>$v){
                if($v['cmt_pro_id']==$val['pro_id']){
                    $news[$key]['mentNum'] += 1;
                }
            }
        }
        // print_r($news);die;
    	$data=array(
    			'news'=>$news,
    			'cid'=>$cid,
                'sid'=>$sid,
                'acate'=>$acate,
    			'scate'=>$scate,
                'ccate'=>$ccate,
    			'show'=>$show,
                'cate'=>$cate,
                'url'=>$url
    		);
        // print_r($attr);die;
    	$this->assign($data);
        $this->display();
    }
    function test(){     
    	if(!empty(IS_POST)){
		$result[] =array_filter($_POST);
		$attr	  =implode("-", $result[0]);
		$attribute='/'.$attr;
        $product = M('Product');
		$res 	=$product->where(array('pro_id'=>$_POST['pro_id']))->select();
		$AllResult=$res[0]['pro_attribute'].$attribute;   //合并之前的数据 
        $mun= $product->where(array('pro_id'=>$_POST['pro_id']))->save(array('pro_attribute'=>$AllResult));
		if($mun){
			echo '添加成功';
		}else{
			echo '添加失败';
		}

		}
    }

	public function testshow(){
		if(!empty(IS_POST)){
			$product = M(Product);
			$res 	=$product->where(array('pro_id'=>$_POST['product_id']))->select();
			$result	=explode('/', $res[0]['pro_attribute']);
//			print_r($result);
//          print_r(count($result));	
			$data[] =array();
			for($i=0;$i<count($result);$i++){
				$data[]=explode('-', $result[$i]); 
			}
			$data=array_filter($data);
			
//			$this->assign($result);
			
			print_r($data);	
		}
	}
	
    public function order(){
        $oldOrder = $_POST['oldOrder'];
        $order = $_POST['order'];
        $id = $_POST['id'];

        $product = M('Product');
        
        $pro = $product->field('pro_id')->where(array('pro_order'=>$order))->find();
        if($pro['pro_id'] != 0){
            $burr_art = $product->where(array('pro_id'=>$pro['pro_id']))->save(array('pro_order'=>$oldOrder));
            $curr_art = $product->where(array('pro_id'=>$id))->save(array('pro_order'=>$order));
            echo json_encode(array('curr_art'=>$curr_art,'burr_art'=>$burr_art));
        }else{
            $curr_art = $product->where(array('pro_id'=>$id))->save(array('pro_order'=>$order));
            echo json_encode($curr_art);
        }
       
    }

    public function delete(){
        $aid = I('get.aid');
        $product = M('Product');
        // unlink('Public/home/'.$art['a_img']);
        $date=$product->where(array('pro_id'=>$aid))->delete();
        if($date){
            echo "<script>window.location.href='/Admin/Product/index';</script>";exit;
        }else{
            echo "<script>alert('删除失败');window.location.href='/Admin/Product/index';</script>";exit;
        }
    }
    //产品批量删除
    public function formDelete(){       
        $aid = I('post.idarr');        //获取需要删除的产品ID，$aid
        if($aid){           //如果$aid有值
            $product = M('Product');
            $where = array('pro_id'=>array('in',$aid));         //生成where条件
            $cmt = M('Procomment');
            $reply = M('Procmt_reply');
            $pcmt = $cmt->where(array('cmt_pro_id'=>array('in',$aid)))->select();       //查找$aid产品的所有评论
            foreach($pcmt as $k=>$v){       //循环遍历出产品评论的ID，保存到新数组$cmt_id下
                $cmt_id[$k] = $v['cmt_id'];
            }
            if($cmt_id){
                $res = $cmt->where(array('cmt_pro_id'=>array('in',$aid)))->delete();        //删除$aid产品的所有评论
                $arr = $reply->where(array('reply_cmtid'=>array('in',$cmt_id)))->delete();          //删除$aid产品的所有评论的回复
            }
            $data = $product->where($where)->delete();     //删除$aid产品
            if($data){ 
                echo "<script>window.location.href='/Admin/Product/index';</script>";exit;
            }else{
                echo "<script>alert('删除失败');window.history.go(-1)";exit;
            }
            
        }else{
            echo "<script>window.location.href='/Admin/Product/index';</script>";exit;      //如果$aid没值，直接跳转到产品首页
        }
    }


    public function isshow(){
        $show = $_POST['show'];
        $id = $_POST['id'];
        $product = M('Product');
        if($show){
            $atc = $product->where(array('pro_id'=>$id))->save(array('pro_isshow'=>0));
        }else{
            $atc = $product->where(array('pro_id'=>$id))->save(array('pro_isshow'=>1));
        }
        echo json_encode($atc);
    }

    public function editor(){
    	$aid = I('get.aid');
        $product=M('Product');
        $procate=M('procate');
		
		$labelObj   =M('label');
		$result_label  =$labelObj->select();
		$label=explode("/", $result_label[0]['label_content']);
		foreach($label as $key=>$value){
		  $result_value[]= explode("-", $value);	
		  	
		}		
        $cate=$procate->where(array('cate_pid'=>0))->select();
        $pro=$product->join('`ast_procate` ON ast_product.pro_classify_id = ast_procate.cate_id')->where(array('pro_id'=>$aid))->find();
         
        $acate = $procate->where(array('cate_id'=>$pro['pro_classify_id']))->find();
        $pid = $acate['cate_pid'];
				
        if($pid == 0){
            $scate = $procate->where(array('cate_pid'=>$acate['cate_id']))->select();
        }else{
            $scate = $procate->where(array('cate_pid'=>$pid))->select();
        }
        $data=array(
                    'pro'=>$pro,
                    'aid'=>$aid,
                    'cate'=>$cate,
                    'acate'=>$acate,
                    'scate'=>$scate,
                    'pid'=>$pid
                );
				
        if(IS_POST){
        		
//			print_r($_POST);die;	
        	  $arr=array(
                    'pro_title'=>I('post.title'),
                    'pro_classify_id'=>I('post.cate'),
                    'pro_subhead'=>I('post.pro_subhead'),
                    'pro_isshow'=> 1,                  
                    'pro_cost_price'=> I('post.member_price'),
                    'pro_current_price'=> I('post.general_price'),
                    'pro_order'=>1,
                    'pro_introduction'=>I('post.content1'),
                );
			
            $res=$product->where(array('pro_id'=>I('post.pro_id') ))->save($arr);
			
            if(0){
                $this->error('更新失败！');
            }else{
          		
                if($_FILES['ban_url']['name']){
                    $thumb = imgFile();
                    $info = $thumb['info'];
                    if(!$info) {// 上传错误提示错误信息
                        $error;
                    }else{// 上传成功
                    	
                        $path = $info['ban_url']['savepath'];
                        $p = ltrim($path,'.');
                        $img = $info['ban_url']['savename'];
                        $src=$p.$img;
                        $root = rtrim($upload->rootPath,'\ThinkPHP/');
                        $roo = str_replace("\\","/",$root);
                        $url = ltrim($info['ban_url']['savepath'],'.');
                        $uploadedfile = $roo.$url.$info['ban_url']['savename'];
                        $oldFile = $roo.$pro['pro_thumb'];
                        unlink($oldFile);//删除原来的缩略图
                        $tmp = imagecreatefromjpeg($uploadedfile);
                        list($width,$height)=getimagesize($uploadedfile);
                        $newwidth=100;
                        $newheight=($height/$width)*$newwidth;
                        img_create_small($uploadedfile,$newwidth,$newheight,$uploadedfile);
                        $result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array('pro_thumb'=>$uploadedfile));
						echo "<script>window.location.href='/Admin/Product/index';</script>";
                    }
                }else{
                    echo "<script>window.location.href='/Admin/Product/index';</script>";
                }
            } 
        }
		
		$labels=explode("-", $data['pro']['pro_labels']);
			
        $this->assign($data);
		$this->assign('result0',$result_value[0]);	
		$this->assign('result1',$result_value[1]);	
		$this->assign('result2',$result_value[2]);
		$this->assign('labels',$labels);
        $this->display();
    }

    public function ProPic_load(){
    	$pro_id =$_GET['id'];
		$pro_photo=$_GET['pic'];
		$product=M('Product');
		$result=$product->where(array('pro_id'=>$pro_id))->select();
		$url   =$product->where(array('pro_id'=>$pro_id))->field($pro_photo)->select();
		$result['pro_photo']=$pro_photo;
		$url_data=$url[0][$pro_photo];
		
		if(IS_POST){
//			print_r($_GET);
//			print_r($_POST);
            if($_FILES['ban_url']['name']){
                $thumb = imgFile();
                 $info = $thumb['info'];
                  if(!$info) {// 上传错误提示错误信息
                        $error;
                    }else{// 上传成功
                        $path = $info['ban_url']['savepath'];
                        $p = ltrim($path,'.');
                        $img = $info['ban_url']['savename'];
                        $src=$p.$img;
                        $root = rtrim($upload->rootPath,'\ThinkPHP/');
                        $roo = str_replace("\\","/",$root);
                        $url = ltrim($info['ban_url']['savepath'],'.');
                        $uploadedfile = $roo.$url.$info['ban_url']['savename'];
                        $oldFile = $roo.$pro['pro_thumb'];
                        unlink($oldFile);//删除原来的缩略图
                        $tmp = imagecreatefromjpeg($uploadedfile);
                        list($width,$height)=getimagesize($uploadedfile);
                        $newwidth=100;
                        $newheight=($height/$width)*$newwidth;
                        img_create_small($uploadedfile,$newwidth,$newheight,$uploadedfile);
						 
						if(strcasecmp($_POST['pro_photo'],"pro_photo1")== 0){
							$result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array("pro_photo1"=>$uploadedfile));
						}
						elseif(strcasecmp($_POST['pro_photo'],"pro_photo2")== 0){
							$result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array("pro_photo2"=>$uploadedfile));
						}
						elseif(strcasecmp($_POST['pro_photo'],"pro_photo3")== 0){
							$result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array("pro_photo3"=>$uploadedfile));
						}
						elseif(strcasecmp($_POST['pro_photo'],"pro_photo4")== 0){
							$result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array("pro_photo4"=>$uploadedfile));
						}
						elseif(strcasecmp($_POST['pro_photo'],"pro_photo5")== 0){
							$result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array("pro_photo5"=>$uploadedfile));
						}
//                     $current_url = get_url();
//					   $this->success("输出的提示信息","/User/list/");
//						header("location:".__APP__."/login");
//						echo "<script>window.location.href='{:U(index.php/Admin/Product/propic,array('aid'=>21))}';</script>";
                        echo "<script> history.go(-2);location.reload();</script>";
                    }
                }else{
                    echo "<script>window.location.href='/Admin/Product/index';</script>";
                }			
		}else{
		
		}

		$this->assign('result',$result);
		$this->assign('url',$url[0][$pro_photo]);
		$this->display();
    } 
    public function ProPic(){
    	$aid = I('get.aid');
        $product=M('Product');
        $procate=M('procate');
		
        $cate=$procate->where(array('cate_pid'=>0))->select();
        $pro=$product->join('`ast_procate` ON ast_product.pro_classify_id = ast_procate.cate_id')->where(array('pro_id'=>$aid))->find();
         
        $acate = $procate->where(array('cate_id'=>$pro['pro_classify_id']))->find();
        $pid = $acate['cate_pid'];
				
        if($pid == 0){
            $scate = $procate->where(array('cate_pid'=>$acate['cate_id']))->select();
        }else{
            $scate = $procate->where(array('cate_pid'=>$pid))->select();
        }
        $data=array(
                    'pro'=>$pro,
                    'aid'=>$aid,
                    'cate'=>$cate,
                    'acate'=>$acate,
                    'scate'=>$scate,
                    'pid'=>$pid
                );
				
        if(IS_POST){
        	echo 123;
			print_r($_POST);
        	print_r($_FILES);die;

            if(!IS_POST){
            	print_r($_FILES);die;
                $this->error('更新失败！');
            }else{
          		print_r($_FILES);die;
                if($_FILES['ban_url']['name']){
                    $thumb = imgFile();
                    $info = $thumb['info'];
                    if(!$info) {// 上传错误提示错误信息
                        $error;
                    }else{// 上传成功
                        $path = $info['ban_url']['savepath'];
                        $p = ltrim($path,'.');
                        $img = $info['ban_url']['savename'];
                        $src=$p.$img;
                        $root = rtrim($upload->rootPath,'\ThinkPHP/');
                        $roo = str_replace("\\","/",$root);
                        $url = ltrim($info['ban_url']['savepath'],'.');
                        $uploadedfile = $roo.$url.$info['ban_url']['savename'];
//						print_r($uploadedfile); die();	
                        $oldFile = $roo.$pro['pro_thumb'];
                        unlink($oldFile);//删除原来的缩略图
                        $tmp = imagecreatefromjpeg($uploadedfile);
                        list($width,$height)=getimagesize($uploadedfile);
                        $newwidth=100;
                        $newheight=($height/$width)*$newwidth;
                        img_create_small($uploadedfile,$newwidth,$newheight,$uploadedfile);
                        $result=$product->where(array('pro_id'=>$_POST['pro_id']))->save(array('pro_thumb'=>$uploadedfile));
						echo "<script>window.location.href='/Admin/Product/index';</script>";
                    }
                }else{
                    echo "<script>window.location.href='/Admin/Product/index';</script>";
                }
            } 
        }
	$resultAll=$data['pro'];

        $this->assign($data);
		
        $this->display();
    }


    public function add(){
        $procate = M('procate');
        $product = M('product');
		$labelObj   =M('label');
		$result_label  =$labelObj->select();
		$label=explode("/", $result_label[0]['label_content']);
		foreach($label as $key=>$value){
		  $result_value[]= explode("-", $value);	
		  	
		}
        $cate = $procate->where(array('cate_pid'=>0))->order('cate_id ASC')->select();
        $maxorder = $product->field('pro_order')->order('pro_order DESC')->limit(1)->find();//文章列表的最大排序
        // dump($maxorder);die;
        if(!$maxorder){
            $order = 1;
        }else{
            $order = $maxorder['pro_order']+1;
        };
        $now  = time();
		$time = date("Ymdhis", $now).'01';
		
        $arr=array();
        if(IS_POST){
			$label1=$_POST['cate_label1'];
			$label2=$_POST['cate_label2'];
			$label3=$_POST['cate_label3'];
			$labels=$label1.'-'.$label2.'-'.$label3;
            $arr=array(
                    'pro_title'=>I('post.title'),
                    'pro_classify_id'=>I('post.cate'),
                    'pro_subhead'=>I('post.author'),
                    'pro_isshow'=> 1,                  
                    'pro_cost_price'=> I('post.member_price'),
                    'pro_current_price'=> I('post.general_price'),
                    'pro_sku'=>$time,
                    'pro_order'=>1,
                    'pro_introduction'=>I('post.content1'),
                    'pro_labels'=>$labels ,
                );
            $scate = I('post.scate');
            $acate = I('post.cate');
            if(!$scate){
                $arr['pro_classify_id']=$acate;
            }else{
                $arr['pro_classify_id']=$scate;
            }
		   
			$res = $product->add($arr);
		if(!$res){
                $this->error('添加失败！');die;
            }else{
                if($_FILES['ban_url']['name']){
					
                    $thumb = imgFile();
                    $info = $thumb['info'];

                    if(!$info) {// 上传错误提示错误信息
                    $info = $thumb['info'];die();
                        $error;
                        
                        die();
                    }else{// 上传成功
                        $path = $info['ban_url']['savepath'];
                        $p = ltrim($path,'.');
                        $img = $info['ban_url']['savename'];
                        $src=$p.$img;
                        $root = rtrim($upload->rootPath,'\ThinkPHP/');
                        $roo = str_replace("\\","/",$root);
                        $url = ltrim($info['ban_url']['savepath'],'.');
                        $uploadedfile = $roo.$url.$info['ban_url']['savename'];
                        $oldFile = $roo.$pro['pro_thumb'];
                        unlink($oldFile);//删除原来的缩略图
                        $tmp = imagecreatefromjpeg($uploadedfile);
                        list($width,$height)=getimagesize($uploadedfile);
                        $newwidth=100;
                        $newheight=($height/$width)*$newwidth;
                        img_create_small($uploadedfile,$newwidth,$newheight,$uploadedfile);
                        $result=$product->where(array('pro_id'=>$res))->save(array('pro_thumb'=>$uploadedfile));
						echo "<script>window.location.href='/Admin/Product/index';</script>";
                    }
                }else{
                    echo "<script>window.location.href='/Admin/Product/index';</script>";
                }
            }            
          
        }
        
        $data=array(
                'cate'=>$cate,
                'time'=>$time,
            );
		$this->assign('result0',$result_value[0]);	
		$this->assign('result1',$result_value[1]);	
		$this->assign('result2',$result_value[2]);	
        $this->assign($data);
        $this->display();
    }

    public function sort(){ //文章分类页
        
        if(IS_POST){    //二级分类添加
            $sec_class = I('post.sec_class');
            $cate_pid = I('post.cate_pid');
            $sec_cate = M('Procate');
            $cate_order = $sec_cate->where(array('cate_pid'=>$cate_pid))->order('cate_order ASC')->limit(1)->select();
            if(!$cate_order){
                $order = 1;
            }else{
                $order = $cate_order[0]['cate_order']+1;
            }
            $arr = array(
                    'cate_order' => $order,
                    'cate_pid' => $cate_pid,
                    'cate_name' => $sec_class
                );
            $res = $sec_cate->add($arr);
        }
        $procate=M('Procate');
        $cate = $procate->order('cate_order ASC')->select();

        //重构分类列表
        foreach($cate as $key => $val){
            if($val['cate_pid'] == 0){
                $one_cate[$key] = $val;
            }
        }
        foreach($one_cate as $k => $v){
            foreach($cate as $ke => $va){
                if($v['cate_id'] == $va['cate_pid']){
                    $one_cate[$k]['sid'][] = $va;
                }
            }
        }
        $data=array(
                'cate'=>$one_cate,
            );

        $this->assign($data);
        $this->display();
    }

    public function fir_test(){
        $fir_class = I('post.fir_class');
        $cate = M('Procate');
        $cate_order = $cate->where(array('cate_pid'=>0))->order('cate_order DESC')->limit(1)->select();
        if(!$cate_order){
            $order = 1;
        }else{
            $order = $cate_order[0]['cate_order']+1;
        }
        $data = array(
                    'cate_pid'=>0,
                    'cate_name'=>$fir_class,
                    'cate_order'=>$order
                );
        $res = $cate->add($data);
        echo json_encode($res);
    }

    public function fir_class(){    //一级分类添加
        if(IS_POST){
            $fir_class = I('post.fir_class');
            $cate = M('Procate');
            $cate_order = $cate->where(array('cate_pid'=>0))->order('cate_order DESC')->limit(1)->select();
            if(!$cate_order){
                $order = 1;
            }else{
                $order = $cate_order[0]['cate_order']+1;
            }
            $data = array(
                    'cate_pid'=>0,
                    'cate_name'=>$fir_class,
                    'cate_order'=>$order
                );
            $res = $cate->add($data);
            if($res){
                echo "<script>window.location.href='/Admin/Product/sort';</script>";
            }
        }
    }

    public function fir_order(){    //一级分类排序
        $order = $_POST['order'];
        foreach($order as $key=>$val){
            $cate = M('Procate');
            $arr = array(
                    'cate_order' => $key+1
                );
            $cateOrder[] = $cate->where(array('cate_name'=>$val))->save($arr);
        }
        echo json_encode($cateOrder);
    }

    public function classification(){   //更改二级分类名称
        $class = $_POST['class'];
        $cate_id = $_POST['cate'];
        $cate = M('Procate');
        $res = $cate->where(array('cate_id'=>$cate_id))->save(array('cate_name'=>$class));
        echo json_encode($res);
    }

    public function cateAjax(){
        $Procate = M('Procate');
		$Label	 =M('Label');
		$labels=$Label->where(array('label_procate_id'=>1))->select();
		$result=explode('/', $labels[0]['label_content']);
		foreach($result as $key=>$value){
		  $res[]= explode("-", $value);	
		  	
		}		
		print_r($res);die;
//      $pid = $_POST['pid'];  
        if($pid){
        	$labels=$Label->where(array('label_procate_id'=>$pid))->order('label_procate_id DESC')->select();
            $data= $Procate->where(array('cate_pid'=>$pid))->order('cate_id DESC')->select();
            echo json_encode($data);
        }
        
    }

    
    
    public function sortAjax(){
        $cid = $_POST['cid'];
        $sort = $_POST['sort'];
        $Procate = M('Procate');
        
        if(!$cid){
            $cate_order = $Procate->where(array('cate_pid'=>0))->order('cate_order DESC')->limit(1)->find();
            if(!$cate_order){
                $order = 1;
            }else{
                $order = $cate_order['cate_order']+1;
            };

            $arr = array(
                    'cate_name'=>$sort,
                    'cate_pid'=>0,
                    'cate_order'=>$order,
                );
            $res = $Procate->add($arr);
            $ret = $Procate->where(array('cate_pid'=>$cid))->select();  
        }else{
            $cate_order = $Procate->where(array('cate_pid'=>$cid))->order('cate_order DESC')->limit(1)->select();
            if(!$cate_order){
                $order = 1;
            }else{
                $order = $cate_order[0]['cate_order']+1;
            };

            $arr=array(
                    'cate_name'=>$sort,
                    'cate_pid'=>$cid,
                    'cate_order'=>$order,
                );
            $res = $Procate->add($arr);
            $ret = $Procate->where(array('cate_pid'=>$cid))->select();
        }
        echo json_encode($ret);
    }

    public function classDel(){     //分类删除及相应文章删除
        $cate_id = I('get.cate_id');
        $cate = M('Procate');
        $pro = M('Product');
        $res = $cate->where(array('cate_id'=>$cate_id))->delete();
        $pro->where(array('pro_classify_id'=>$cate_id))->delete();
        $cate->where(array('cate_pid'=>$cate_id))->delete();
        if($res){
            echo "<script>window.location.href='/Admin/Product/sort';</script>";
        }
    }

    public function cateDelete(){
        $cid = I('post.idarr');
        if($cid){
            $cate = M('Procate');
            $pro = M('Product');            
            foreach($cid as $key=>$value){
                $cate_id=$cate->field('cate_id')->where(array('cate_pid'=>$key))->select();
                // dump($cate_id);die;
                $data=$cate->where(array('cate_id'=>$key))->delete();
                $cate->where(array('cate_pid'=>$key))->delete();
                $pro->where(array('pro_classify_id'=>$key))->delete();
                foreach($cate_id as $val){
                    $pro->where(array('art_cateid'=>$val['cate_id']))->delete();
                }   
            }

            if($data){
                    echo "<script>window.location.href='/Admin/Product/sort';</script>";exit;
                }else{
                    echo "<script>alert('删除失败');window.location.href='/Admin/Product/sort';</script>";exit;
                }
            
        }else{
            echo "<script>window.location.href='/Admin/Product/sort';</script>";exit;
        }
    }

    public function comment(){
        if(IS_GET){
            $aid = I('get.pid');
            $cmt = M('Procomment');
            $current = I('get.page',1);
            $limit = 10;
            $art = ($current-1)*$limit;
            $url = get_url();//获取当前页面的URL地址
            $fir = strpos($url,'page');
            if($fir){
                $purl = mb_substr($url,0,$fir-1);
            }else{
                $purl = $url;
            }
            $count=$cmt->count();
            $res = $cmt->where(array('cmt_pro_id'=>$aid))->limit($art,$limit)->select();
            $reply = M('Procmt_reply');
            $rep = $reply->select();
            foreach($res as $key=>$val){
                $res[$key]['cmt_ctitle'] = str_cut($val['cmt_content'], 0, 21);
                foreach($rep as $k=>$v){
                    if($v['reply_cmtid']==$val['cmt_id']){
                        $res[$key]['cmt_reply'][] = $v;
                    }
                }
            }
            $show = list_page($current,$limit,$count,$purl);
            $data = array(
                    'cmt'=>$res,
                    'show'=>$show
                );
            $this->assign($data);
            $this->display();
        }   
        if(IS_POST){
            $id = I('post.idarr');
            $cmt = M('Procomment');
            $reply = M('Procmt_reply');
            $cres = $cmt->where(array('cmt_id'=>array('in',$id)))->delete();
            $rres = $reply->where(array('reply_cmtid'=>array('in',$id)))->delete();
            $url = get_url();
            if($cres){
                echo "<script>window.location.href='".$url."';</script>";exit;
            }else{
                echo "<script>alert('删除失败');window.location.href='".$url."';</script>";exit;
            }
        }

    }
    public function commentDel(){
        $acid = I('get.acid');
        $cmt = M('Artcomment');
        $res = $cmt->where('acid')->delete();
        $a = $cmt->getLastSql();
        print_r($a);die;
        $url = get_url();
        if($res){
            echo "<script>window.location.href='".$url."';</script>";
        }else{
            echo "<script>alert('删除失败');window.location.href='".$url."';</script>";
        }
    }

}    