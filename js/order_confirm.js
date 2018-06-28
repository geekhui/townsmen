(function () {
    "use strict";

    valiLogin();
    var pid = getUrlParams().pid;
    var cart_id = getUrlParams().cart_id;

    (function () {
        //获取页面内容
        if (pid || cart_id) {
            $.ajax({
                type: 'post',
                url: preHttp + 'getOrder',
                xhrFields: { withCredentials: true },
                crossDomain: true,
                data: { pid: pid, cart_id: cart_id },
                success: function success(res) {
                    console.log(res);
                    var html = "";
                    if (res.code == 200) {
                        if (res.address && res.address[0]) {
                            html = '<dt>\u59D3\u540D\uFF1A</dt><dd>' + res.address[0].name + '</dd><dt>\u624B\u673A\uFF1A</dt><dd>' + res.address[0].phone + '</dd><dt id="userAddress" name="' + res.address[0].aid + '">\u5730\u5740\uFF1A</dt><dd>' + res.address[0].province + ' ' + res.address[0].city + ' ' + res.address[0].area + ' ' + res.address[0].address + '</dd> ';
                        } else {
                            html = '<p style="text-align:center;margin: 1rem 0;"><a href="new_address.html"> \u60A8\u8FD8\u6CA1\u6709\u5730\u5740,\u524D\u5148\u53BB\u65B0\u5EFA\uFF01 </a></p>';
                        }
                        $("#address").html(html);

                        html = "";
                        var _iteratorNormalCompletion = true;
                        var _didIteratorError = false;
                        var _iteratorError = undefined;

                        try {
                            for (var _iterator = res.details[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                                var d = _step.value;

                                html += '<li><dl class="flex_between goods_details" data-goodsId="' + d.id + '"><dt> <img src="' + (preLink + d.logo) + '" alt=""> </dt><dd class="goods_cont"><h3>' + d.sname + '</h3><p class="score"> <span><i></i>\u79EF\u5206 </span> <span class="goods_score">' + d.score + '</span>';

                                if (d.price && parseInt(d.price) > 0) {
                                    html += '+<b>\uFFE5</b> <span class="goods_price">888</span>';
                                }

                                html += '</p><p>\u5E02\u573A\u53C2\u8003\u4EF7\uFF1A' + d.realprice + '\u5143</p></dd></dl><p class="flex_between amount"><span>\u6570\u91CF</span><span>x <i data-type="amount">' + (d.num || 1) + '</i></span></p></li>';
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

                        if (res.freight) {
                            html += '<p class="freight">\u603B\u8BA1\u8FD0\u8D39\uFF1A <span><i data-price="freight">' + res.freight + '</i> \u5143</span></p>';
                        }

                        $("#order").html(html);
                    } else if (res.code == 401) {
                        location.href = "login.html";
                    } else if (res.code == 201) {
                        alert("数据库数据不全");
                    }
                }
            });
        }
    })();

    $(".mask").on("click", function () {
        $(this).hide();
        $(".pop_pay").hide();
    });

    //付款设置
    $(".pay_methods>p").on("click", function () {
        var isSelected = $(this).find("i").hasClass("checked");
        if (!isSelected) {
            $(this).find("i").addClass("checked").parent().siblings().find("i").removeClass("checked");
        }
    });

    $("[data-type=pay-now]").click(function (e) {
        e.preventDefault();

        var pid_array = $("[data-goodsId]");
        var num_array = $("[data-type=amount]");

        var pid = parseInt(pid_array[0].getAttribute('data-goodsId'));
        var num = parseInt(num_array[0].innerHTML);
        for (var i = 1; i < pid_array.length; i++) {
            num += '/' + num_array[i].innerHTML;
            pid += '/' + pid_array[i].getAttribute('data-goodsId');
        }
        if ($("#userAddress").length == 0) {
            $(".pop_warn").html('<i>请选择收货地址</i>').show();
            setTimeout(function () {
                $(".pop_warn").hide();
            }, 3000);
            return;
        }

        $.ajax({
            type: 'post',
            url: preHttp + 'createOrder',
            xhrFields: { withCredentials: true },
            crossDomain: true,
            data: {
                pid: pid, num: num,
                freight: $("[data-price=freight]")[0].innerHTML,
                address_id: $("#userAddress").attr("name")
            },
            success: function success(res) {
                console.log(res);
                if (res.code == 200) {
                    //弹出支付窗口
                    $(".mask").show();
                    $(".pop_pay").show();

                    var score = 0,
                        price = 0;
                    var countScore = $(".goods_score").length;
                    var countPrice = $(".goods_price").length;
                    var html = "";
                    //积分总和、价格总和 
                    if (countPrice > 0) {
                        $(".pay_methods").show();
                        for (var j = 0; j < countPrice; j++) {
                            price += parseInt($(".goods_price")[j].innerHTML);
                        }
                    }
                    for (var i = 0; i < countScore; i++) {
                        score += parseInt($(".goods_score")[i].innerHTML);
                    }

                    html += '<span><i></i>\u79EF\u5206 </span> <span id="totalScore" class="bg_number">' + score + '</span>';
                    if (countPrice > 0) {
                        html += '<span class="bg_price">\uFF0B \uFFE5' + price + '</span>';
                    }

                    $("[data-price=total] p").html(html);
                } else {
                    //订单创建错误弹窗
                    $(".pop_warn").html('<i>订单创建错误,请稍候重试</i>').show();
                    setTimeout(function () {
                        $(".pop_warn").hide();
                    }, 3000);
                }
            },
            error: function error() {
                console.log("ajax error");
                $(".pop_warn").html('<i>订单创建错误,请稍候重试</i>').show();
                setTimeout(function () {
                    $(".pop_warn").hide();
                }, 3000);
            }
        });
    });

    $("#pay").click(function () {
        var userInfo = getUserInfo();
        var userScore = parseInt(userInfo.score);
        var score = parseInt($("#totalScore").html());
        var uid = parseInt(sessionStorage.getItem("zdkjuid"));
        userScore = 10000000000000; // test
        if (userScore < score) {
            // 积分不足时弹窗提示
            $(".pop_warn").html("<i>您的积分不足！</i>").show();
            setTimeout(function () {
                $(".pop_warn").hide();
            }, 2500);
            return;
        }

        $.ajax({
            type: 'post',
            url: preHttp + 'vaile_token',
            xhrFields: { withCredentials: true },
            crossDomain: true,
            data: { utoken: localStorage.getItem("zdkjutoken") },
            success: function success(res) {
                console.log(res);
                if (res.code == 400) {
                    window.location.href = "login.html";
                }
            },
            error: function error() {
                console.log("ajax err");
            }
        }).then(function () {});

        //跳转支付页面
        // window.location.href = "";            
    });
})();