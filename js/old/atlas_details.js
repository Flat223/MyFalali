define(function(require){
	var $ = require('jquery');
	require('common');
	require('swiper.min');
		
	var handle = {
		init:function(){
			 var swiper = new Swiper('.swiper-container', {
				pagination: '.swiper-pagination',
				paginationClickable: true,
				nextButton: '.swiper-button-next',
				prevButton: '.swiper-button-prev',
				spaceBetween: 0,
				paginationType : 'fraction',
				paginationFractionRender: function (swiper, currentClassName, totalClassName) {
					return '<span class="' + currentClassName + '"></span>' +
							' / ' +
							'<span class="' + totalClassName + '"></span>';
				}
			});
		}
	};
	(function(){
		handle.init();
	})();
});

