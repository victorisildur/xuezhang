//mannually bind
function CommentViewModel(){
	var self = this;
	self.status = ko.observable('ohyeah');
	self.user_name = ko.observable("isildur");
	self.comment = ko.observable("pee");
	self.light_count = ko.observable("1111");
	
	 $.post("http://xuezhang.duapp.com/shop_lists.php?action=new", 
	{'b':encodeURIComponent('嘿嘿')},
	function(allData) {
        // Update view model properties
		if( allData.status == 'ok')
		{		
			self.status(allData.status);
			//self.user_name(allData.comments[0].user_name);
			//self.comment(allData.comments[0].comment);
			//self.light_count(allData.comments[0].light_count);
		}
    },"json");  
}
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