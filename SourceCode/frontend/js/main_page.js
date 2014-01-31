//榜单view model
function GoodsNew(data) {
	this.gid = data.gid;
	this.img_url = data.img_url;
}

function GoodsTop(data) {
	this.gid = data.gid;
	this.img_url = data.img_url;
	this.price = data.price;
	this.sale_num = data.sale_num;
}

function Comments(data) {
	this.status = data.status;
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
}

function MainpageViewModel() {
	var self = this;
	self.goods_top = ko.observableArray([]);
	self.goods_new = ko.observableArray([]);
		
	//获取新品
	$.post( "http://xuezhang.duapp.com/shop_lists.php?action=new",
	{"num":3,
	 "count":0
	},
	function(data) {
		if(data.status == "ok") {
			var mappedGoodsNew = $.map(data.goods_new, function(item) {return GoodsNew(item)});
			goods_new = mappedGoodsNew;
		}
		else {
			console.log("post new fails:"+data.msg);
		}
	},"json"
	);
	
	//获取榜单
	$.post( "http://xuezhang.duapp.com/shop_lists.php?action=rank",
	{"num" : 3,
	 "count" : 0
	},
	function(data) {
		if(data.status == "ok")	{
			var mappedGoodsTop = $.map(data.goods_top, function(item) {return new GoodsTop(item)} );
			self.goods_top(mappedGoodsTop);
		}
		else {
			console.log(data.msg);
		}
	},"json"	);
	
	self.comments = ko.observableArray([]);
	
	//获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":3,
	 "count":0,
	 "type":"rank"
	},
	function(data) {
        // Update view model properties
		if( data.status == 'ok')
		{		
			var mappedComments = $.map(data.comments, function(item) {return new Comments(item)} );
			self.comments(mappedComments);
		}
    },"json");  
	
	//定时获取评论
	$("body").everyTime("5s", function() {
	//获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":3,
	 "count":0,
	 "type":"rank"
	},
	function(data) {
        // Update view model properties
		if( data.status == 'ok') {		
			var mappedComments = $.map(data.comments, function(item) {return new Comments(item)} );
			self.comments(mappedComments);
		}
    },"json");  
	});
	
	//亮了
	self.lightComment = function() {
		var func_this = this;
		//修改后端亮数
		$.post("http://xuezhang.duapp.com/shop_comments.php?action=rank",
		{"comment_id":func_this.comment_id}, 
		function(data){
			
		},"json");
		//修改本地亮数
		
			//func_this = comment[x],已经取出light_count值啦
			var light_count = parseInt(func_this.light_count);
			console.log("comments[x].light_count:" + light_count);
			
			var light_count_plus = light_count+1;
			console.log("light_count+1="+light_count_plus);
			
			//添加新项
			self.comments.unshift({
					"user_name":func_this.user_name,
					"comment":func_this.comment,
					"comment_id":func_this.comment,
					"light_count":light_count_plus});	
	};
}

//bind
ko.applyBindings(new MainpageViewModel());

