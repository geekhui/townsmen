(function () {
    "use strict";

    valiLogin();
    $.ajax({
        type: 'get',
        url: preHttp + 'getAllOrder',
        xhrFields: { withCredentials: true },
        crossDomain: true,
        success: function success(res) {
            // console.log(res);
            var html = "";
            if (res.code == 401) location.href = "login.html";
            if (res.code == 200) {
                for (var i = 0; i < res.data.length; i++) {
                    var datalist = res.data[i];
                    var u = res.unique[i];
                    if (datalist.length > 1) html += '<li class="flex_between smaller" data-type="item">';else html += '<li class="flex_between" data-type="item">';
                    var _iteratorNormalCompletion = true;
                    var _didIteratorError = false;
                    var _iteratorError = undefined;

                    try {
                        for (var _iterator = datalist[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                            var d = _step.value;

                            html += '<div class="img"><a href="order_detail.html?order_number=' + d.order_number + '"><img src="' + (preLink + d.logo) + '"></a> </div><div class="pay_cont"><h4>' + d.sname + '</h4><p class="order_price"><i></i> \u79EF\u5206 <span>' + d.score + '</span> ';

                            if (d.price && parseInt(d.price) > 0) {
                                html += '<span>&nbsp;+<b>\uFFE5</b>' + d.price + '</span>';
                            }

                            html += '</p><p class="amount">\xD7 ' + d.number + '</p></div>';
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

                    html += '<p class="order_number">\u8BA2\u5355\u53F7\uFF1A<span>' + u.order_number + '</span>';
                    if (u.status == -2) html += '<a class="logistics" href="javascript:;"> \u8BA2\u5355\u5173\u95ED </a></p></li>';
                    if (u.status == -1) html += '<a class="logistics" href="order_detail.html?order_number=' + u.order_number + '"> \u53BB\u4ED8\u6B3E </a></p></li>';
                    if (u.status == 0) html += '<a class="logistics" href="javascript:;"> \u7B49\u5F85\u53D1\u8D27 </a></p></li>';
                    if (u.status == 1) html += '<a class="logistics" href="https://m.kuaidi100.com/index_all.html?type=\'+ detail[0].delivery_type +"&postid="+ detail[0].tracking_number +"&callbackurl="+ window.location.href "> \u67E5\u770B\u7269\u6D41 </a></p></li>';
                    if (u.status == 2) html += '<a class="logistics" href="javascript:;"> \u4EA4\u6613\u5B8C\u6210 </a></p></li>';
                }
            } else if (res.code == 201) {
                html = '<div class="none"> <img src="images/mark.jpg" alt="image"/><p>\u8FD8\u6CA1\u6709\u8BA2\u5355\u5466\uFF01</p> </div>';
            }
            $("[data-type=order]").html(html);
            $(".pay_cont").on("click", function () {
                window.location.href = $(this).prev().find("a").attr('href');
            });
        },
        error: function error() {
            console.log("err");
        }
    }).then(function () {
        var timeOutEvent = 0;
        $("[data-type=item]").on({
            //监听长按事件
            touchstart: function touchstart() {
                var that = this;
                var status = $(that).find(".logistics").html();
                if (status == "交易完成") timeOutEvent = setTimeout(function () {
                    longPress(that);
                }, 800);
            },
            touchmove: function touchmove() {
                clearTimeout(timeOutEvent);timeOutEvent = 0;
            },
            touchend: function touchend() {
                clearTimeout(timeOutEvent);timeOutEvent = 0;
            }
        });

        //  $("[data-type=order]").on("")
    });

    function longPress(target, num) {
        //长按弹框执行删除订单操作
        var cancleorder = num || $(target).find(".order_number >span").html();
        $("#confirm").show();
        $("#disagree").click(function () {
            $("#confirm").hide();
        });
        $("#agree").click(function () {
            $("#confirm").hide();
            $.ajax({
                type: 'post',
                url: preHttp + 'deleteOrder',
                xhrFields: { withCredentials: true },
                data: { cancleorder: cancleorder },
                success: function success(res) {
                    $(target).remove();
                    console.log(res);
                }
            });
        });
    }
})();