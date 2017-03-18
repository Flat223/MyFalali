define(function(require){
	var $ = require('jquery');
	require('area_dat.js');
	var provinces = areadata.provinces;
	var cities = areadata.cities;
	var counties = areadata.counties;
	
	return {
		init:function(province,city,county){
			var html = "<option value='0'>请选择</option>";
			for(var i in provinces){
				html += "<option value='"+provinces[i].id+"'>"+provinces[i].name+"</option>";
			}
			$("."+province).html(html);
			$("."+city).html("<option value='0'>请选择</option>");
			$("."+county).html("<option value='0'>请选择</option>");
			$("."+province).on("change",function(){
				$("."+county).html("<option value='0'>请选择</option>");
				var id = $(this).val();
				if(id == 0){
					$("."+city).html("<option value='0'>请选择</option>");
					return;
				}
				var html = "";
				html += "<option value='0'>请选择</option>";
				for(var i in cities){
					if(cities[i].parent_id == id){
						html += "<option value='"+cities[i].id+"'>"+cities[i].name+"</option>";
					}
				}
				$("."+city).html(html);
			});
			$("."+city).on("change",function(){
				var id = $(this).val();
				if(id == 0){
					$("."+county).html("<option value='0'>请选择</option>");
					return;
				}
				var html = "";
				html += "<option value='0'>请选择</option>";
				for(var i in counties){
					if(counties[i].parent_id == id){
						html += "<option value='"+counties[i].id+"'>"+counties[i].name+"</option>";
					}	
				}
				$("."+county).html(html);
			});
		}	
	};
	
});