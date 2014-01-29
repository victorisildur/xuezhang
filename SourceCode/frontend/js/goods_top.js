//榜单view model

function GoodsTop(data) {
	this.goods_id = data.goods_id;
	this.img_url = data.img_url;
	this.price = data.price;
	this.sale_num = data.sale_num;
}

function GoodsTopViewModel() {
	var self = this;
	self.goods_top = ko.observableArray([]);
	console.log("self.goods_top");
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
ko.applyBindings(new GoodsTopViewModel());

