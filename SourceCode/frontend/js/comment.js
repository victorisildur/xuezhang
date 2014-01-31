//comment bind
function Comments(data) {
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
	this.comment_id = data.comment_id;
	
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
	$("body").everyTime("5s", function() {
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
		self.newUserName("");
    };
	
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
ko.applyBindings(new CommentViewModel());