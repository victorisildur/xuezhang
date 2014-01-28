//Model of Comment,也就是数据
function Comment(data) {
    this.user_name = ko.observable(data.user_name);
	this.comment = ko.observable(data.comment);
    this.comment_id = ko.observable(data.comment_id);
	this.light_count = ko.observable(data.light_count);
}


//获取评论json
function CommentViewModel(){
	
	//fetch data from server
	$.getJSON("/backend_url", function(allData) {
        var mappedComment = $.map(allData, function(item) { return new Comment(item) });
        self.tasks(mappedComment);
    }); 
}

ko.applyBindings(new CommentsViewModel());
