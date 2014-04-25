$(document).ready( function() {
	
});


 

function OrderConstructor(data){
	
	this.num = ko.observable(data.num);
	this.detail_id = data.detail_id;
	this.color = ko.observable(data.color);
	this.size = ko.observable(data.size);
}


function OrderViewModel() {
	var detailIdJson = eval("("+shop_goods_detail+")");
	var didArray = detailIdJson.did_array;
	var self = this;
	self.orderFull = ko.observableArray([]);
	
	//获取gid,
	var href = window.location.href;
	var index = href.indexOf('=');
	if(index != -1) {
		var gid = href.substr(index+1);
	};
	console.log("gid:"+gid);
	
	//color,size,time,sex
	self.color = ko.observable();
	self.size = ko.observable();
	self.time = ko.observable();
	self.sex = ko.observable();
	//left
	self.left = ko.computed( function() {
		var i = findIndexInDidArrayMatchingSelection();
		console.log("index:"+i);
		if(i!= -1 )
		{
			var left = didArray[i].left;
			return left;
		} else {
			return 0;
		}
	});
	
	//输入的name,phone,addr,num值
	self.newNameText = ko.observable();
	self.newPhoneText = ko.observable();
	self.newAddrText = ko.observable();
	self.newNumText = ko.observable();
	//加一单按钮文本
	self.addOrderBtnText = ko.observable("预览订单");
	
	//加一单
	self.addOrder = function(){
		console.log("press add order btn");
		console.log("select color:"+this.color() );
		this.addOrderBtnText("加一单");
		
		pushAllInputsIntoOrderFull();
	}
	
	
	
	//删除订单
	self.deleteOrder = function(orderItem) {
		self.orderFull.remove(orderItem);
	}
	
	//提交订单
	self.submitOrder = function() {
		console.log("press submit order btn");
		    pushAllInputsIntoOrderFull();
			//send to shop_takeorder.php
			var mydata = { "real_name": this.newNameText,
						   "phone": this.newPhoneText,
						   "address" : this.newAddrText,
						   "message" : this.newMsgTest,
						   "best_time" : this.time(),
						   "orders": ko.toJS(self.orderFull)
							  };
			$.post("shop_takeorder.php?gid="+gid,
				mydata, function(data) {
					if(data.status=='ok')
					{
						window.location.href = "index.php"
					}
				},"json"
			);
		
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
	
	//return index in did_array that matches 'sex','color','size' that we selected
	//if no one matches, return -1
	function findIndexInDidArrayMatchingSelection() {	
		var i=0;
		while(i<detailIdJson.did_array.length) {
			if(detailIdJson.did_array[i].color==self.color() &&
			   detailIdJson.did_array[i].size ==self.size()  &&
			   detailIdJson.did_array[i].sex  ==self.sex() )
				break;
			i++;
		}
		if(i==detailIdJson.did_array.length)
		{
			return -1;
		} else {
			return i;
		}	
	}
	
	//把当前input加入到orderFull数组
	function pushAllInputsIntoOrderFull() {
		if( isPhone(self.newPhoneText()) && isChinese(self.newNameText()) ) {
			//console.log("select color in if:"+this.color() );
			
			var i = findIndexInDidArrayMatchingSelection();
			if(i== -1 )
			{
				alert("没有这款库存！");
			} else {
				var selectedDetailId = detailIdJson.did_array[i].detail_id;
				console.log("detail_id matched!! value is :" + selectedDetailId);
			}
			
			var orderItem = {
							detail_id:selectedDetailId,
							num: Number(self.newNumText()),
							color: self.color(),
							size:  self.size()
							};
			self.orderFull.push(new OrderConstructor(orderItem));
		}
		else {
			alert("手机号码或姓名输入不正确");
		}
	}
}

ko.applyBindings(new OrderViewModel());