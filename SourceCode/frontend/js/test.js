//comment bind
function Comments(data) {
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
	this.comment_id = data.comment_id;	
}

function GoodsTop(data) {
	this.goods_id = data.goods_id;
	this.img_url = data.img_url;
	this.price = data.price;
	this.sale_num = data.sale_num;
}

function CommentViewModel(){
	var self = this;
	self.comments = ko.observableArray([]);
	self.newUserName = ko.observable();
	self.newCommentText = ko.observable();
	
	//初始化获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":5,
	 "count":0,
	 "type":"time"
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
	$("body").everyTime("10s", function() {
	//获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":5,
	 "count":0,
	 "type":"time"
	},
	function(data) {
        // Update view model properties
		if( data.status == 'ok')
		{		
			var mappedComments = $.map(data.comments, function(item) {return new Comments(item)} );
			self.comments(mappedComments);
		}
    },"json");  
	});
	
	//提交评论
	self.submitComment = function() {  
		var newUserName = this.newUserName();
		var newCommentText = this.newCommentText();		
		//local add comment, how to make my comment head of the array?
		//push<-->tail, unshift<-->head
        self.comments.unshift(new Comments({ 
			user_name: newUserName,
			comment: newCommentText,
			light_count: 0}));
		//submit to the server
		$.post("http://xuezhang.duapp.com/shop_comments.php?action=submit",
		{"user_name" : newUserName,
		 "comment" : newCommentText,
		 "gid" : 1
		},
		function(data) {
			if(data.status=="ok") {
				console.log("submit ok");
			}
		},"json"
		);
		//restore input
		self.newCommentText("");
    };
	
	//亮一个本地化,未完成
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
		console.log("is_light == 1");
		var temp1 = self.comments.slice(1,2).user_name;
		var temp2 = temp1.user_name;
		var temp3 = self.comments.slice(1,2).join();
		var temp4 = self.comments.toString();
		console.log("comments[0].user_name:" + temp1);
		
		for(var i=0; self.comments[i] != null; i++) {
			console.log(i);
			if(self.comments[i].comment_id == func_this.comment_id) {
				console.log("comment_id found");
				//替换数组第i项
				self.comments.splice(i,1,{"user_name":func_this.user_name,
					"comment":func_this.comment,
					"comment_id":func_this.comment,
					"light_count":func_this.light_count+1});
			}
		}; 
	};
	
	//获取榜单
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
			console.log("status:"+data.status);
		}
		else
		{
			console.log(data.msg);
		}
	},"json"	);
}

//bind
ko.applyBindings(new CommentViewModel());


//button
function postHeiHei() {
	$.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{'b':encodeURIComponent('嘿嘿')},
	function(allData) {
        $("#test_paragraph").text(allData.status);
		//document.write(data.status);
    },"json"); 
}