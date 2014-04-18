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
	self.comments = ko.observableArray([{"user_name":"柳絮","comment":"ab","light_count":2,"comment_id":55}]);	
	self.newUserName = ko.observable();
	self.newCommentText = ko.observable();
	
	//初始化获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":5,
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
	
	//定时获取评论
	/*
	$("body").everyTime("5s", function() {
	//获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":1,
	 "num":5,
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
	*/
	
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
	
	//数组comments操作测试
	//var temp = self.comments.spice(0,1).user_name;
	//console.log("outer:" + temp);
	$("body").everyTime("3s", function() {
		//push没问题
		//self.comments.unshift({"user_name":"柳絮","comment":"ab","light_count":2,"comment_id":55});
		//slice有问题！
		//var temp = self.comments.slice(0,1);
		//var temp1 = self.comments.indexOf(temp);
		//console.log("user_name:"+temp1);
		//self.comments.splice(0,1);
	});
	
	//亮一个本地化,未完成
	self.lightComment = function() {
		var func_this = this;
		//修改后端亮数
		$.post("http://xuezhang.duapp.com/shop_comments.php?action=rank",
		{"comment_id":func_this.comment_id}, 
		function(data){
			
		},"json");
		//修改本地亮数
		/*
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
		*/				
		//请求更新
		$.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
		{"gid":1,
		 "num":5,
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
	},"json");
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