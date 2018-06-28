"use strict";

(function () {
        var order_number = getUrlParams().order_number;
        if (!order_number) window.location.href = "login.html";
        valiLogin();

        $.ajax({
                type: "GET",
                url: preHttp + "getOrder",
                xhrFields: { withCredentials: true },
                crossDomain: true,
                data: { order_number: order_number },
                success: function success(response) {
                        // console.log(response);
                        if (response.code == 401) location.href = "login.html";
                        if (response.code == 200) {
                                //地址信息
                                var address = response.address[0];
                                var html = "<h4 style=\"background:#fff;margin-bottom: 10px;\">\u6536\u8D27\u5730\u5740\u4FE1\u606F\uFF1A</h4><dt>\u59D3\u540D\uFF1A</dt><dd>" + address.name + "</dd><dt>\u624B\u673A\uFF1A</dt><dd>" + address.phone + "</dd><dt>\u5730\u5740\uFF1A</dt><dd>" + address.province + "  " + address.city + " " + address.area + " " + address.address + "</dd>";
                                $("#address").html(html);
                                //订单状态信息
                                var detail = response.details;
                                $(".tracking").html(detail[0].tracking_number || "");
                                // console.log(detail[0].status)
                                switch (detail[0].status) {
                                        case "-2":
                                                html = "已关闭";
                                                break;
                                        case "-1":
                                                html = "未付款";$("#status>a").html("去支付").attr("href", "javascript:;").show();
                                                break;
                                        case "0":
                                                html = "等待发货";
                                                break;
                                        case "1":
                                                html = "物流中";$("#status>a").html("查看物流").show().attr("href", "https://m.kuaidi100.com/index_all.html?type=" + detail[0].delivery_type + "&postid=" + detail[0].tracking_number + "&callbackurl=" + window.location.href);
                                                break;
                                        case "2":
                                                html = "交易完成";$("#status>a").html("查看物流").show().attr("href", "https://m.kuaidi100.com/index_all.html?type=" + detail[0].delivery_type + "&postid=" + detail[0].tracking_number + "&callbackurl=" + window.location.href);
                                                break;
                                }
                                $(".status").html(html);
                                //商品信息
                                html = "";
                                var price = 0,
                                    score = 0;
                                var _iteratorNormalCompletion = true;
                                var _didIteratorError = false;
                                var _iteratorError = undefined;

                                try {
                                        for (var _iterator = detail[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                                                var d = _step.value;

                                                html += "<div class=\"img\"><a href=\"goods_details.html?pid=" + d.pid + "\"><img src=\"" + (preLink + d.logo) + "\"></a> </div><div class=\"pay_cont\"><h4>" + d.sname + "</h4><p class=\"order_price\"><i></i> \u79EF\u5206 <span>" + d.score + "</span> ";

                                                if (d.price && Number(d.price) > 0) {
                                                        html += "<span>&nbsp;+<b>\uFFE5</b>" + d.price + "</span>";
                                                }

                                                html += "</p><p class=\"amount\">\xD7 " + d.number + "</p></div>";
                                                price += d.price * d.number; //精度丢失问题
                                                score += d.score * d.number;
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

                                html += "<p class=\"order_number\">\u8BA2\u5355\u53F7\uFF1A<span>" + d.order_number + "</span>" + (d.status !== -1 ? "<a class='cancle' href='javascript:;'> 取消订单 </a></p>" : '</p>');

                                $("#order li").html(html);
                                //价格总计
                                $(".total_score").html(score.toFixed(0) + " 积分");
                                $(".total_price").html(price.toFixed(2) + " 元");

                                var pay_time, deliver_time, end_time;
                                //相关时间信息
                                html = "<li>\u8BA2\u5355\u7F16\u53F7\uFF1A " + detail[0].order_number + "</li><li>\u652F\u4ED8\u5B9D\u4EA4\u6613\u53F7\uFF1A 1651651416746584651684161</li><li>\u521B\u5EFA\u65F6\u95F4\uFF1A " + detail[0].creat_time + "</li><li>" + (pay_time = detail[0].deal_time ? "付款时间：" + detail[0].deal_time : '') + "</li><li>" + (pay_time = detail[0].delivery_time ? "发货时间：" + detail[0].delivery_time : '') + "</li><li>" + (pay_time = detail[0].finish_time ? "结束时间：" + detail[0].finish_time : '') + "</li>";

                                $("#timer").html(html);
                        }
                }
        }).then(function () {
                $(".cancle").click(function () {
                        $.ajax({
                                type: 'POST',
                                url: preHttp + "cancleOrder",
                                xhrFields: { withCredentials: true },
                                crossDomain: true,
                                data: { order_number: order_number },
                                success: function success(res) {
                                        if (res.code == 200) {
                                                location.reload();
                                        }
                                }
                        });
                });
        });
})();