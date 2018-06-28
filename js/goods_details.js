(function () {
    "use strict";

    getUserInfo();
    function setPageInfo(res) {
        var freight = res.base_freight[0];
        var res = res[0],  html;
        $(".goods_bgimg>img").attr("src", res.pic);
        $(".goods_cont>h3").html(res.title || res.sname);
        $("[data-type=score]").html(res.score);
        $("[data-type=price]").html(res.price ? "+ ￥ "+res.price : "");
        $("[data-type=realPrice]").html(res.realprice ? res.realprice : "暂无");

        if (res.freight_type == 2) {
            html = '\u8FD0\u8D39\uFF1A \u5305\u90AE ';
        } else {
            html = '\u8FD0\u8D39\uFF1A' + freight.default_cost + ' \u5143\u8D77';
        }

        $(".freight").html(html);

        html = '<li> <i>\u54C1\u540D\uFF1A</i> ' + res.sname + '</li><li> <i>\u54C1\u724C\uFF1A</i>' + res.title + '</li><li> <i>\u8BE6\u60C5\uFF1A</i>' + (res.detail || '暂无商品详情') + '</li>';

        $("#productInfo").html(html);
    }
    //获取url传参

    var params = getUrlParams();
    var pid = params.pid;
    (function() { 
        if (sessionStorage.getItem("_zdkj_goodsPage") && JSON.parse(sessionStorage.getItem("_zdkj_goodsPage")).pid == pid ) {
            // console.log('session')
            var res = JSON.parse(sessionStorage.getItem("_zdkj_goodsPage")).res;
            return setPageInfo(res);
        }

        $.ajax({
            type: 'get',
            url: preHttp + 'getGoodsDetails',
            data: { pid: pid },
            success: function success(res) {
                // console.log(res)
                setPageInfo(res);
                sessionStorage.setItem("_zdkj_goodsPage",JSON.stringify({'pid': pid, 'res': res}))
            },
            error: function error() {
                console.log("获取数据错误");
            }
        });
    })();

    $(".add_cart").click(function () {
        valiLogin();
        $.ajax({
            type: 'post',
            url: preHttp + 'addCart',
            xhrFields: { withCredentials: true },
            crossDomain: true,
            data: { pid: pid },
            success: function success(res) {
                var html = "";
                switch (res.code) {
                    case 401:
                        window.location.href = "login.html";break;
                    case 301:
                        html = "<i>购物车中已存在该商品</i>";break;
                    case 302:
                        html = "<i>购物车已满，请先清理</i>";break;
                    case 200:
                        html = "<i>添加购物车成功</i>";break;
                    case 201:
                        html = "<i>添加失败，请稍候重试</i>";break;
                }

                $(".pop").html(html).show();
                setTimeout(function () {
                    $(".pop").css("opacity", 0);
                    setTimeout(function () {
                        $(".pop").hide().css("opacity", 1);
                    }, 500);
                }, 1500);
            },
            error: function error(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    });

    $(".buy_now").click(function () {
        //判断登录  未登录则立即跳转登录
        valiLogin();
        window.location.href = "order_confirm.html?pid=" + pid;
    });
})();