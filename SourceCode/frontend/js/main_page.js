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
	this.user_name = data.user_name;
	this.comment = data.comment;
	this.light_count = data.light_count;
	this.comment_id = data.comment_id;	
}

function MainpageViewModel() {
	var self = this;
	self.goods_top = ko.observableArray([]);
	self.goods_new = ko.observableArray([]);
	self.comments = ko.observableArray([]);  
	
	//获取新品
	$.post( "http://xuezhang.duapp.com/shop_lists.php?action=new",
	{"num":3,
	 "count":0
	},
	function(data) {
		if(data.status == "ok") {
			var mappedGoodsNew = $.map(data.goods_new, function(item) {return new GoodsNew(item)} );
			self.goods_new(mappedGoodsNew);
		}
		else {
			console.log("post new fails:"+data.msg);
		}
	},"json"
	);
	
	//获取榜单
	$.post( "http://xuezhang.duapp.com/shop_lists.php?action=rank",
	{"num" : 15,
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
	
}

//bind
ko.applyBindings(new MainpageViewModel());

