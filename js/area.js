define(function(require){
	var $ = require('jquery');
	var data = require("area_dat");
	
	var provinces = data.provinces;
	var cities = data.cities;
	var counties = data.counties;
	
	return {
		init:function(province,city,county,defaultProvince,defaultCity,defaultCounty){
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
			if(county != undefined){
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
			if(defaultProvince == undefined || defaultCity == undefined || defaultCounty == undefined){
				return;
			}
			$("."+province).val(defaultProvince).change();
			$("."+city).val(defaultCity).change();
			$("."+county).val(defaultCounty);
		}
	};
});