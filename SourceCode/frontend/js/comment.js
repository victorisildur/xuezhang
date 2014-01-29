//comment bind
function Comments(data) {
	this.status = data.status;
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
}

function CommentViewModel(){
	var self = this;
	self.comments = ko.observableArray([]);
		
	 $.post("http://xuezhang.duapp.com/shop_comments.php?action=get", 
	{"gid":2,
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
}
//bind
ko.applyBindings(new CommentViewModel());