function Details(data) {
	this.img_url = data.img_url;
}

function Comments(data) {
	this.status = data.status;
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
}

function DetailViewModel(){
	var self = this;
	self.comments = ko.observableArray([]);
	self.detailImgs = ko.observableArray([]);
	self.newUserName = ko.observable();
	self.newCommentText = ko.observable();
	
	self.price = ko.observable();
	self.sale_num = ko.observable();
	self.title = ko.observable();
	self.description = ko.observable();
	//bind for purchase
	self.gid = ko.observable();
	
	//获取gid
	var gid = document.getElementById("gid_holder").value;
	
	//获取detail接口数据(未测试)
	$.post("http://xuezhang.duapp.com/details.php?action=detail", 
	{"gid":gid},
	function (data){
		if(data.status == ok) {
			console.log("detail post ok");
			//goods_detail[img_url]
			var mappedDetailImgs = $.map(data.goods_detail, function(item) {return new Details(item)} );
			self.detailImgs(mappedDetailImgs);
			//desctiption
			price = data.price;
			sale_num = data.sale_num;
			title = data.title;
			description = data.description;			
		}
	},"json"
	);*/
	
	//初始化获取评论
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":gid,
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
		 "gid" : gid
		},
		function(data) {
			if(data.status=="ok") {
				console.log("submit ok");
			}
		},"json"
		);
		//restore input
		self.newCommentText("");
		self.newUserName("");
    };
	
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
ko.applyBindings(new DetailViewModel());