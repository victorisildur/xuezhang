function OrderConstructor(data){
	this.own_color = ko.observable(data.color);
	this.own_num = ko.observable(data.num);
	this.own_size = ko.observable(data.size);
}


function OrderViewModel() {
	//获取gid,
	var href = window.location.href;
	var index = href.indexOf('?');
	if(index != -1) {
		var gid = href.substr(index+1);
	};
	console.log("gid:"+gid);
	
	
	
	var self = this;
	self.orderFull = ko.observableArray([]);
	
	//color,size,time
	self.color = ko.observable();
	self.size = ko.observable();
	self.time = ko.observable();
	
	//输入的name,phone,addr,num值
	self.newNameText = ko.observable();
	self.newPhoneText = ko.observable();
	self.newAddrText = ko.observable();
	self.newNumText = ko.observable();
	//加一单按钮文本
	self.addOrderBtnText = ko.observable("预览订单");
	
	//加一单
	self.addOrder = function(){
		this.addOrderBtnText("加一单");
		
		if(isPhone(this.newPhoneText()) && isChinese(this.newNameText()) ) {
			var orderItem = {color:this.color(),
							num:this.newNumText(),
							size:this.size()
							};
			self.orderFull.push(new OrderConstructor(orderItem));
		}
		else {
			alert("手机号码或姓名输入不正确");
		}
	}
	
	//删除订单
	self.deleteOrder = function(orderItem) {
		self.orderFull.remove(orderItem);
	}
	
	//提交订单
	self.submitOrder = function() {
		if(isPhone(this.newPhoneText()) && isChinese(this.newNameText()) ) {
			//send to backend
			$.ajax("http://xuezhang.duapp.com/shop_order.php", {
				data: ko.toJSON({ gid: gid,
							  name: this.newNameText,
							  phone: this.newPhoneText,
							  addr : this.newAddrText,
							  time : this.time(),
							  order: self.orderFull
							  }),
				type: "post", contentType: "application/json",
				success: function(result) { alert(result.status) }
			});
		}
    };
	
	//判断手机号
	function isPhone(inputString)
    {
	    var partten = /^1[3,5,8]\d{9}$/;
	    var fl=false;
	    if(partten.test(inputString))
	    {
	        console.log('是手机号码');
	        return true;
	    }
	    else
	    {
			console.log('不是手机号码');
	        return false;
	    }
    }
	
	//判断汉字
	function isChinese(inputString) {
		var pattern = new RegExp("[\\u4E00-\\u9FFF]","g");
		return pattern.test(inputString);
	}
}

ko.applyBindings(new OrderViewModel());