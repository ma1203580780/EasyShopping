//上传ppt
	$(function(){
		$('.addPpt').click(function(){
			$('.addFile').css('display','block');
		});
	});
//教案上传
	$(function(){
		$('.addTeachingPlan').click(function(){
			var plan = $('.addPost').attr('action','/plan');
			$('.addFile').css('display','block');
		});
	});
//关闭弹出层
	$(function(){
		$('.exit').click(function(){
			//$('.addFile').css('display','none');
			$('.addFile').hide(1200);
		});
	});