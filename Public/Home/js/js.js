$(function(){
	
	$('.head_top .right a:first-child').click(function(){
		if($('.head_top .right ul').is(':visible')){
			$('.head_top .right ul').slideUp();
		}else{
			$('.head_top .right ul').finish().slideDown();
		};
	});
	
	$('.submit_orders .address > div').click(function(){
		$(this).addClass('on').siblings().removeClass('on');
	});
	
	$('.all_select_checkbox').not('.no').children('input').click(function(){
		if($(this).is(':checked')){
			$(this).attr('checked','checked');
			$('.select_checkbox').not('.no').children('input').attr('checked','checked');
			$(this).siblings('span').children().show();
			$('.select_checkbox').not('.no').find('span img').show();
		}else{
			$(this).removeAttr('checked');
			$('.select_checkbox').not('.no').children('input').removeAttr('checked');
			$(this).siblings('span').children().hide();
			$('.select_checkbox').not('.no').find('span img').hide();
		};
	});
	
	$('.pay_way li').click(function(){
		$(this).addClass('on').siblings().removeClass('on');
		$(this).find('input[name=pay_way]')[0].click();
	});
	
	$('.pay_block .more').click(function(){
		$('.pay_way').height('auto');
	});
	
	if($('#password').length > 0){
		var focus = false;
		var inputs = $('#password').val().length;
		$('.pay_block .password .input .password_block').click(function(){
			if(focus){
				return false;
			}else{
				$('#password')[0].focus();
				focus = true;
				$('.pay_block .password .input .password_block > div:eq('+inputs+')').addClass('on');
				$('.pay_block .password .input .password_block > div:eq('+inputs+') span').before('<p>|</p>');
				text($('.pay_block .password .input .password_block > div:eq('+inputs+') p'));
			};
		});
		$('#password').keyup(function(e){
			if(focus){
				if(inputs > 6){
					return false;
				}else{
					inputs = $('#password').val().length;
					$('.pay_block .password .input .password_block > div p').remove();
					$('.pay_block .password .input .password_block > div').eq(inputs).addClass('on').siblings().removeClass('on');			
					$('.pay_block .password .input .password_block > div:eq('+inputs+') span').before('<p>|</p>');
					text($('.pay_block .password .input .password_block > div:eq('+inputs+') p'));
					$('.pay_block .password .input .password_block > div span b').hide();
					$('.pay_block .password .input .password_block > div').each(function(i,e){
						if(i < inputs){
							$(e).find('span b').show();
						};
					});
				};		
			};
		});
		$('#password').blur(function(){
			focus = false;
			$('.pay_block .password .input .password_block > div p').remove();
			$('.pay_block .password .input .password_block > div').removeClass('on');
		});
	};
	
	$('.pay_complete_tab a').mouseenter(function(){
		$(this).addClass('on').siblings().removeClass('on');
		$('.pay_complete_tab_block ul').eq($(this).index()).show().siblings().hide();
	});
	
	$('.member_info_list .dropdown > a').click(function(){
		if($(this).next('ul').is(':visible')){
			$(this).next('ul').slideUp();
		}else{
			$(this).next('ul').finish().slideDown();
		};
	});
	
	
	$('.pfxx').each(function(i,e){
		$(e)[0].pf = 0;
		$(e)[0].s = false;
		$(e).children('span').mouseenter(function(){
			if($(e)[0].s){
				return false;
			}else{
				$(e)[0].pf = $(this).index();
				$(e).children('span').each(function(i2,e2){
					if(i2 <= $(e)[0].pf){
						$(e2).css({color:'#f7c766',textShadow:'0 -1px 1px #f8994c'});
					}else{
						$(e2).removeAttr('style');
					};
					$(e2).click(function(){
						$(e)[0].s = true;
					});
				});
			};
		});
		$(e).mouseleave(function(){
			if($(e)[0].s){
				return false;
			}else{
				$(e).children('span').removeAttr('style');
			};
		});
	});
	
	$('.show_pic .row2 .pic a').click(function(){
		$(this).parent().remove();
		$('.show_pic .limit').text($('.show_pic .row2 .pic').length+"/3");
	});
	
	$('.sort a').click(function(){
		$(this).addClass('on').siblings().removeClass('on');
	});
	
	$('.goods_detail .pics a').click(function(){
		var src = $(this).children('img').attr('src');
		$(this).addClass('on').siblings().removeClass('on');
		$('.goods_detail .pic_wrap img').attr('src',src);
	});
});

function text(obj){
	setTimeout(function(){
		obj.hide();
	},488);
	setInterval(function(){
		obj.show();
		setTimeout(function(){
			obj.hide();
		},488);
	},988);
};
