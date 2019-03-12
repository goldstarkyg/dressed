function showAll(catname, order){
	location.href = '/'+catname+'/views?order='+order;
}
function showQAAll(catname, qcatname, order){
	location.href = '/'+catname+'/'+qcatname+'/views?order='+order;
}
function viewArticle(slug, order){
	location.href = '/'+slug+'?order='+order;
}
function viewQAArticle(catname, articleid, order){
	location.href = '/'+catname+'?id='+articleid+'&order='+order;
}
function viewtag(tagname, order){
	location.href = '/tag/'+tagname+'?order='+order;
}
function searchHomeArticle(){
	$('#homesearchform').submit();
}
function searchHomeArticleRefresh(order){
	$('#order').val(order);
	$('#homesearchrefreshform').submit();
}
(function($){
	$('.body-content').css('min-height', $(window).height()-200);

})(jQuery);
function clickYes(cat_id, article_id, user_id, confirm){
	var data = {
		cat_id: cat_id,
		article_id: article_id,
		user_id: user_id,
		confirm: confirm,
		'_token': $('#_token').val()
	};
	$.ajax({
		url: "/article/comment",
		type: 'POST',
		data: data,
		success:function(data){
			console.log(data);
			if(data == 1){
				//empty quiz
				$('#commentbt').css('display', 'none');
				$('#commentyes').css('display', 'inline-block');
			}
		},
		error:function(request,status,error){
			//alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
}
function clickCommentSubmit(cat_id, article_id, user_id, confirm){
	var comment = $('#comment').val();
	var data = {
		cat_id: cat_id,
		article_id: article_id,
		user_id: user_id,
		comment: comment,
		confirm: confirm,
		'_token': $('#_token').val()
	};
	$.ajax({
		url: "/article/comment",
		type: 'POST',
		data: data,
		success:function(data){
			console.log(data);
			if(data == 1){
				//empty quiz
				$('#commentbt').css('display', 'none');
				$('#commentyes').css('display', 'inline-block');
				$('#commentno').css('display', 'none');
			}
		},
		error:function(request,status,error){
			//alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
}
function clickNo(){
	$('#commentbt').css('display', 'none');
	$('#commentno').css('display', 'inline-block');
}