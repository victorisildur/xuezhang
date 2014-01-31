//榜单view model

function GoodsTop(data) {
	this.goods_id = data.goods_id;
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

function GoodsTopViewModel() {
	var self = this;
	self.goods_top = ko.observableArray([]);
	console.log("self.goods_top");
	//获取榜单
	$.post( "http://xuezhang.duapp.com/shop_lists.php?action=rank",
	{"num" : 3,
	 "count" : 0
	},
	function(data) {
		if(data.status == "ok")
		{
			var mappedGoodsTop = $.map(data.goods_top, function(item) {return new GoodsTop(item)} );
			self.goods_top(mappedGoodsTop);
		}
		else
		{
			console.log(data.msg);
		}
	},"json"	);
	
	self.comments = ko.observableArray([]);
	
	//获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":5,
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
	
	//亮了
	self.lightComment = function() {
		var func_this = this;
		var is_lighted = 0;
		$.post("http://xuezhang.duapp.com/shop_comments.php?action=rank",
		{"comment_id":func_this.comment_id},
		function(data){
			if( data.status == 'ok')
			{					
				//让亮了立即+1
				//一定要吧这一条Comments.light_count改掉
				is_lighted = 1;
				console.log("light is ok");
				console.log("is_lighted:"+is_lighted);
			}
		},"json");
	};
}

//bind
ko.applyBindings(new GoodsTopViewModel());

