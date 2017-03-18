$(function(){
	var goodsTypes = new Array();
	var num = 1;
	console.log(data.length);
	for(var i in data){
		if(data[i].parentId == undefined || data[i].parentId=='297ee1fa4e665851014e6658dec80000'){
			var first = {};
			first.name = data[i].categoryName;
			first.id = num;
			first.parentid = 0;
			first.selfid = data[i]._id;
			first.level = 1;
			goodsTypes.push(first);
			num++;
		}
	}
	for(var i in goodsTypes){
		var first = goodsTypes[i];
		first.subTypes = new Array();
		for(var j in data){
			if(data[j].parentId == first.selfid){
				var second = {};
				second.name = data[j].categoryName;
				second.id = num;
				second.parentid = first.id;
				second.selfid = data[j]._id;
				second.level = 2;
				first.subTypes.push(second);
				num++;
			}
		}
	}
	for(var i in goodsTypes){
		if(goodsTypes[i].subTypes){
			for(var j in goodsTypes[i].subTypes){
				var second = goodsTypes[i].subTypes[j];
				second.subTypes = new Array();
				for(var k in data){
					if(data[k].parentId == second.selfid){
						var third = {};
						third.name = data[k].categoryName;
						third.id = num;
						third.parentid = second.id;
						third.level = 3;
						third.selfid = data[k]._id;
						second.subTypes.push(third);
						num++;
					}	
				}	
			}			
		}
	}
	
/*
	$.ajax({
		type:'post',
		dataType:'json',
		data:{data:JSON.stringify(goodsTypes)},
		url:'/do/test.html',
		success:function(data){
			alert(data.ret);
		},
		error:function(){
			alert('shi bai le');
		}
	});
*/
	
	
	
	
	
	console.log(goodsTypes);
	var left = new Array();
	
	a:for(var i in data){
		aa:for(var j in goodsTypes){
			if(data[i]._id == goodsTypes[j].selfid){
				continue a;
			}
			b:for(var k in goodsTypes[j].subTypes){
				if(data[i]._id == goodsTypes[j].subTypes[k].selfid){
					continue a;
				}
				c:for(var m in goodsTypes[j].subTypes[k].subTypes){
					if(data[i]._id == goodsTypes[j].subTypes[k].subTypes[m].selfid){
						continue a;
					}
				}
			}	
		}	
		var aaa = [];
		aaa.name = data[i].categoryName;
		aaa.selfid = data[i]._id;
		left.push(aaa);	
	}
	console.log(left);
	console.log(num);
	
	
	
	
	
});