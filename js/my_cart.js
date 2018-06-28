(function () {
    "use strict";

    valiLogin();
    getUserInfo();
    //总价计算
    function countPrice() {
        var count = $(".cart_box .radio_class .checked");
        var points = 0;
        var price = 0;
        for (var i = 0; i < count.length; i++) {
            var $li = $(count[i]).parent().parent();

            var amount = $li.find("[data-btn=reduce]").prev().html();
            var value = $li.find(".bg_price").html();

            if (value == undefined) {
                value = 0;
            }
            points += parseInt(amount * $li.find(".bg_number").html());
            price += parseInt(amount * value);
        }
        $("#requirePoints").html(points);
        $("#goodsPrice").html(price);
    }

    (function () {
        $.ajax({
            type: 'get',
            url: preHttp + 'getCart',
            xhrFields: { withCredentials: true },
            crossDomain: true,
            success: function success(res) {
                console.log(res);
                if (res.code == 401) location.href = "login.html";
                if (res.code == 200) {
                    $(".footer").show();

                    var data = res.array,
                        html = "";
                    var _iteratorNormalCompletion = true;
                    var _didIteratorError = false;
                    var _iteratorError = undefined;

                    try {
                        for (var _iterator = data[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                            var d = _step.value;

                            html += "<li data-cart=\"item\"><div class=\"radio_class\" data-type=\"radio\"><i ></i></div><div> <a href=\"goods_details.html?pid=" + d.pid + "\"><img src=\"" + (preLink + d.logo) + "\" alt=\"\"></a></div><div class=\"goods_cont\"><h3>" + d.sname + "</h3><p class=\"score\"> <span><i></i>\u79EF\u5206 </span> <span class=\"bg_number\">" + d.score + "</span>";

                            if (d.price && parseInt(d.price) > 0) {
                                html += "+ <b>\uFFE5</b> <span class=\"bg_price\">" + d.price + "</span>";
                            }

                            html += "</p><p>\u5E02\u573A\u53C2\u8003\u4EF7:" + d.realprice + "\u5143 </p><div><a href=\"javascript:;\" data-btn=\"add\" class=\"add\">+</a><span data-cart=\"num\"> " + d.num + " </span><a href=\"javascript:;\" data-btn=\"reduce\" class=\"reduce\">-</a></div></div><div class=\"cart_status\"><a href=\"" + d.cart_id + "\">\u5220\u9664</a></div></li>";
                        }
                    } catch (err) {
                        _didIteratorError = true;
                        _iteratorError = err;
                    } finally {
                        try {
                            if (!_iteratorNormalCompletion && _iterator.return) {
                                _iterator.return();
                            }
                        } finally {
                            if (_didIteratorError) {
                                throw _iteratorError;
                            }
                        }
                    }
                } else if (res.code == 201) {
                    html = "<div class=\"none\"><p><img src=\"images/cart1.jpg\" alt=\"image\"/></p> <p>\u8D2D\u7269\u8F66\u7ADF\u7136\u662F\u7A7A\u7684\uFF01</p></div>";
                }

                $("[data-type=cart]").html(html);
            }
        }).then(function () {

            countPrice();

            //checkbox操作
            $("[data-type=radio]").click(function () {
                $(this).find("i").toggleClass("checked");
                var status = $(".cart_box .checked").length == $(".cart_box ul li").length;
                if (status) {
                    $("[data-type=selectAll]").find("i").addClass("checked");
                } else {
                    $("[data-type=selectAll]").find("i").removeClass("checked");
                }
                countPrice();
            });

            //物品数量加减按钮动作
            $("[data-btn=reduce]").click(function () {
                var value = parseInt($(this).prev().html());
                if (value == 1) return;
                $(this).prev().html("" + (value - 1));

                countPrice();
            });

            $("[data-btn=add]").click(function () {
                var value = parseInt($(this).next().html());
                if (value == 99) return;
                $(this).next().html("" + (value + 1));

                countPrice();
            });

            // 获取所有行，对每一行设置监听
            var lines = $("[data-cart=item]");
            var len = lines.length;
            var lastX, lastXForMobile;

            //左移距离 (css已设置30%)
            var leftDistance = lines.width() / 5;

            // 用于记录被按下的对象
            var pressedObj;
            var lastLeftObj;

            // 用于记录按下的点
            var start;

            $(lines).on("click", ".cart_status a", function (e) {
                e.preventDefault();
                var $this = $(e.target);
                var cart_id = $this.attr("href");
                $this.parent().parent().remove();
                //底部总价变化
                countPrice();

                //数据库更改数据
                $.ajax({
                    type: 'get',
                    url: preHttp + 'deleteCartItem',
                    xhrFields: { withCredentials: true },
                    crossDomain: true,
                    data: { cart_id: cart_id },
                    success: function success(result) {
                        console.log(result);
                    },
                    error: function error() {
                        console.log("未知错误，商品删除失败");
                    }
                });
            });

            // 网页在移动端运行时的监听
            $(lines).on({
                touchstart: function touchstart(e) {
                    lastXForMobile = e.changedTouches[0].pageX;
                    pressedObj = this;

                    // 记录开始按下时的点
                    var touches = event.touches[0];
                    start = {
                        x: touches.pageX,
                        y: touches.pageY
                    };
                },
                touchmove: function touchmove(e) {
                    // 计算划动过程中x和y的变化量
                    var touches = event.touches[0];
                    var distance = {
                        x: touches.pageX - start.x,
                        y: touches.pageY - start.y
                    };
                    // 横向位移大于纵向位移，阻止纵向滚动
                    if (Math.abs(distance.x) > Math.abs(distance.y)) {
                        event.preventDefault();
                    }
                },
                touchend: function touchend(e) {
                    if (lastLeftObj && pressedObj != lastLeftObj) {
                        $(lastLeftObj).animate({ marginLeft: "0" }, 500); // 右滑
                        lastLeftObj = null; // 清空上一个左滑的对象
                    }
                    var diffX = e.changedTouches[0].pageX - lastXForMobile;
                    if (diffX < -leftDistance / 2) {
                        $(pressedObj).animate({ marginLeft: "-30%" }, 500); // 左滑
                        lastLeftObj && lastLeftObj != pressedObj && $(lastLeftObj).animate({ marginLeft: "0" }, 500); // 已经左滑状态的按钮右滑
                        lastLeftObj = pressedObj; // 记录上一个左滑的对象
                    } else if (diffX > leftDistance / 2) {
                        if (pressedObj == lastLeftObj) {
                            $(pressedObj).animate({ marginLeft: "0" }, 500); // 右滑
                            lastLeftObj = null; // 清空上一个左滑的对象
                        }
                    }
                }
            });
        });
    })();

    // 网页在PC浏览器中运行时的监听
    /*   $(lines).on({
        mousedown : function(e){
            lastX = e.clientX;
            pressedObj = this; 
        },
        mouseup : function(e){
            if (lastLeftObj && pressedObj != lastLeftObj) {     // 点击除当前左滑对象之外的任意其他位置
                $(lastLeftObj).animate({marginLeft:"0"}, 500);  // 右滑
                lastLeftObj = null;                             
            }
            var diffX = e.clientX - lastX;
            if (diffX < -leftDistance/2 ) {
                $(pressedObj).animate({marginLeft: "-30%" }, 500); // 左滑
                lastLeftObj && lastLeftObj != pressedObj && 
                    $(lastLeftObj).animate({marginLeft:"0"}, 500); // 已经左滑状态的按钮右滑
                lastLeftObj = pressedObj;                           // 记录上一个左滑的对象
    
            } else if (diffX > leftDistance/2 ) {
                if (pressedObj == lastLeftObj) {
                $(pressedObj).animate({marginLeft:"0"}, 500);   // 右滑
                lastLeftObj = null;                             // 清空上一个左滑的对象
                }
            }
        }
    });           */

    $("[data-type=selectAll]").on("click", function () {
        $(this).find("i").toggleClass("checked");
        //  $(this).find("i").hasClass("checked") 

        if ($(this).find("i").hasClass("checked")) {
            $("[data-type=radio] i").addClass("checked");
        } else {
            $("[data-type=radio] i").removeClass("checked");
        }
        countPrice();
    });

    $("#settlement").click(function () {
        var cartArray = $("[data-type=radio] .checked");
        var cartIdArray = [],
            cartNumArray = [];
        if (cartArray.length == 0) {
            return;
        }
        for (var i = 0; i < cartArray.length; i++) {
            //遍历多个参数并合并
            var _cart_id = $(cartArray[i]).parent().parent().find(".cart_status a").attr("href");
            var _num = $(cartArray[i]).parent().parent().find("[data-cart=num]").html().replace(/\s+/g, "");
            cartIdArray.push(_cart_id);
            cartNumArray.push(_num);
        }

        var cart_id = cartIdArray[0];
        var num = cartNumArray[0];
        for (var _i = 1; _i < cartIdArray.length; _i++) {
            cart_id += '/' + cartIdArray[_i];
            num += '/' + cartNumArray[_i];
        }
        console.log(cart_id, num);
        $.ajax({
            type: 'post',
            url: preHttp + 'commitDemand',
            xhrFields: { withCredentials: true },
            crossDomain: true,
            data: { cart_id: cart_id, num: num },
            success: function success(res) {
                console.log(res);
                if (res.code == 200) {
                    window.location.href = "order_confirm.html?cart_id=" + cart_id;
                } else {
                    setPop("订单创建失败，请稍候重试");
                }
            },
            error: function error() {
                setPop("订单创建失败，请稍候重试");
            }
        });
    });
})();