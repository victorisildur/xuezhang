//click button
$(document).ready(function() {
	$("#comment_section").hide();
});

$("#comment_tap").click( function(){
	$("#detail_section").hide();
	$("#detail_tap").removeClass();
	$("#detail_tap").addClass("nav_unselected");
	$("#comment_section").show();
	$("#comment_tap").removeClass();
	$("#comment_tap").addClass("nav_selected");
});
		
$("#detail_tap").click( function(){
	$("#detail_section").show();
	$("#detail_tap").removeClass();
	$("#detail_tap").addClass("nav_selected");
	$("#comment_section").hide();
	$("#comment_tap").removeClass();
	$("#comment_tap").addClass("nav_unselected");	
});

//get comments
function Comments(data) {
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
	this.comment_id = data.comment_id;
}

function DetailViewModel(){
	var self = this;
	self.comments = ko.observableArray([]);
	self.newUserName = ko.observable();
	self.newCommentText = ko.observable();
	
	//self.purchase_url = ko.observable();
	
	//获取gid,
	var href = window.location.href;
	var index = href.indexOf('=');
	if(index != -1) {
		var gid = href.substr(index+1);
	};
	console.log("gid:"+gid);
	var order_url = 'shop_order.php?gid='+gid;
	self.purchase_url = ko.observable(order_url);
	console.log(self.purchase_url);
	
	//初始化获取评论
	 $.post("shop_comments.php?action=get", 
	{"gid":gid,
	 "num":5,
	 "count":0,
	 "type":"rank"
	},
	function(data) {
        // Update view model properties
		if( data.status == 'ok'){		
			var mappedComments = $.map(data.comments, function(item) {return new Comments(item)} );
			self.comments(mappedComments);
		}
    },"json"); 
	
	
	//提交评论
	self.submitComment = function() {  
		var newUserName = this.newUserName();
		var newCommentText = this.newCommentText();	
		console.log("submit comment");
		console.log("entered user name "+newUserName);
		console.log("entered comment "+newCommentText);
		if( newUserName != '' && newCommentText != '')
		{
			//local add comment, how to make my comment head of the array?
				//push<-->tail, unshift<-->head
			self.comments.unshift(new Comments({ 
				  user_name: newUserName,
					comment: newCommentText,
				light_count: 0}));
			//submit to the server
			$.post("shop_comments.php?action=submit",
			{"user_name" : newUserName,
			 "comment" : newCommentText,
			 "gid" : gid
			},
			//callback
			function(data) {
				if(data.status=="ok") {
					console.log("submit ok");
				}
			},"json"
			);
			//restore input
			self.newCommentText("");
			self.newUserName("");
		}
		else
		{
			alert("请输入用户名或评论内容");
		}
    };
	
	//亮了
	//提交亮了
	self.lightComment = function() {
		var func_this = this;
		console.log("comment id "+this.comment_id);
		if( $.isNumeric(this.comment_id) )
		{
			//修改后端亮数
				$.post("shop_comments.php?action=rank",
				{"comment_id":func_this.comment_id}, 
				function(data){
					if(data.status == 'ok') {
						//请求更新
						$.post("shop_comments.php?action=get", 
						{"gid":gid,
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
					}
				},"json");	
		}
		else
		{
			alert("不能自己亮自己哦");
		}
	};
	
}

//bind
ko.applyBindings(new DetailViewModel());