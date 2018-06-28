(function () {
	"use strict";

	//轮播图
	function setIndexInfo(response) {
		var banner = response.banner;
		var html = "";
		for (var i = 0; i < banner.length; i++) {
			html += '<li class="swiper-slide" ><a href="' + (banner[i].link || 'javascript:;') + '"><img src="' + (preLink + banner[i].logo) + '""></a></li>';
		}
		$("#banner").html(html);

		html = "";
		var hotSales = response.hotSales;
		for (var j = 0; j < hotSales.length; j++) {
			html += '<li class="item swiper-slide"><div><img src="' + (preLink + hotSales[j].logo) + '" onerror="this.src=\'images/goods_02.jpg\'" alt=""/> </div><div >\t<h3>' + hotSales[j].sname + '</h3><p class="recom_price">\u5E02\u573A\u53C2\u8003\u4EF7\uFF1A' + hotSales[j].realprice + '\u5143</p><p class="recom_score"><i>' + hotSales[j].score + '</i> \u79EF\u5206';

			if (hotSales[j].price && hotSales[j].price > 0) {
				html += '<span> + \uFFE5 <i>' + hotSales[j].price + '</i></span>';
			}
			html += '</p></div><a href="goods_details.html?pid=' + hotSales[j].id + '" >\u5151\u6362</a></li>';
		}
		$("#hotSales").html(html);
		expandClick("#hotSales li");
		//silder
		var bannerSwiper = new Swiper('.banner', {
			autoplay: {
				delay: 5000,
				disableOnInteraction: false
			},
			loop: true
		});

		var recomSwiper = new Swiper('#recomGoods', {
			slidesPerView: 'auto',
			spaceBetween: 0,
			autoplay: {
				delay: 5000,
				disableOnInteraction: false
			},
			loop: true
		})
	}

	(function () {
		if (sessionStorage.getItem("_zdkj_indexInfo")) {
			// console.log('session')
			var response = JSON.parse( sessionStorage.getItem("_zdkj_indexInfo") );
			return setIndexInfo(response);
		}
		$.ajax({
			type: 'get',
			url: preHttp + 'getIndexInfo',
			xhrFields: { withCredentials: true },
			crossDomain: true,
			success: function success(response) {
				// console.log(response)
				if (response && response.code==200) {
					sessionStorage.setItem('_zdkj_indexInfo', JSON.stringify(response));
					setCookie( "PHPSESSID", response.phpsessid );
					setIndexInfo(response);
				}
			},
			error: function error() {
				alert("服务器崩溃，请稍候重试！");
			}
		})
	})();

	getUserInfo();	
	//积分栏 
	var userInfo, score;
	if ( sessionStorage.getItem('_zdkj_userInfo') ) {  userInfo = JSON.parse(sessionStorage.getItem('_zdkj_userInfo')) }

	$("[data-info=score]").html( score = (userInfo && userInfo.score)? userInfo.score : '****'); 


	//推荐及分类
})();