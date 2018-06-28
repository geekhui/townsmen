(function () {
    "use strict";

    valiLogin();
    $.ajax({
        type: 'get',
        url: preHttp + 'getAddress',
        xhrFields: { withCredentials: true },
        crossDomain: true,
        success: function success(result) {
            console.log(result);
            if (result.code == 401) {
                location.href = "login.html";
            }
            if (result.code == 201) {
                var html = '<div class="none"> <p><img src="images/home.jpg" alt="image"/></p> <p>\u8FD8\u6CA1\u6709\u6536\u8D27\u5730\u5740\u5466\uFF01</p> </div>';
            } else if (result.code == 200) {
                var data = result.data;
                var html = "";
                var _iteratorNormalCompletion = true;
                var _didIteratorError = false;
                var _iteratorError = undefined;

                try {
                    for (var _iterator = data[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                        var d = _step.value;

                        html += '<li data-type="item"><div class="item"><dl class="item_start" data-type="address"><dt>\u59D3\u540D\uFF1A</dt><dd>' + d.name + '</dd><dt>\u624B\u673A\uFF1A</dt><dd>' + d.phone + '</dd><dt>\u5730\u5740\uFF1A</dt><dd>' + d.province + ' ' + d.city + ' ' + d.area + ' ' + d.address + '</dd></dl><div class="item_middle">';

                        if (d.selected == 1) {
                            html += '<p><i class="checked"></i> <span>\u9ED8\u8BA4\u5730\u5740</span> </p>';
                        } else {
                            html += '<p><i></i> <span>\u8BBE\u4E3A\u9ED8\u8BA4</span> </p>';
                        }
                        html += '<a href="new_address.html?aid=' + d.aid + '" data-type="edit" ><i class="fa fa-pencil"></i> \u7F16\u8F91</a></div></div><div class="delete"><a class="item_end" href="' + d.aid + '">\u5220\u9664</a></div></li>';
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
            }

            $("#addressInfo").html(html);
        }
    }).then(function () {

        moveLeftDelete();

        $(".item_middle >p").on("click", 'i', function (e) {
            e.preventDefault();
            var $this = $(e.target);
            if ($this.parent().children("i").hasClass("checked")) {
                return;
            }

            $("[data-type=item] .item_middle p i").removeClass("checked").next().html("设为默认");
            $this.addClass("checked").next().html("默认地址");
        });

        //删除地址
        $("[data-type=item]").on("click", ".item_end", function (e) {
            e.preventDefault();
            var $this = $(e.target);
            var aid = $this.attr("href");
            $this.parent().parent().remove();

            //数据库更改数据
            $.ajax({
                type: 'post',
                url: preHttp + 'deleteAddress',
                xhrFields: { withCredentials: true },
                crossDomain: true,
                data: { aid: aid },
                success: function success(result) {
                    console.log(result);
                },
                error: function error() {
                    console.log("未知错误，商品删除失败");
                }
            });
        });

        //设置默认地址
        $(".item_middle >p>i").on("click", function () {
            if (this.getAttribute('class') == 'checked') return;
            var aid = $(this).parent().parent().parent().parent().find(".item_end").attr("href");

            $.ajax({
                type: 'post',
                url: preHttp + 'setDefaultAddress',
                xhrFields: { withCredentials: true },
                crossDomain: true,
                data: { aid: aid },
                success: function success(result) {
                    console.log(result);
                },
                error: function error() {
                    console.log("未知错误，商品删除失败");
                }
            });
        });
    });

    function moveLeftDelete() {
        // 获取所有行，对每一行设置监听
        var lines = $("[data-type=item]");
        var len = lines.length;
        var lastX, lastXForMobile;

        //左移距离 (css已设置30%)
        var leftDistance = lines.width() / 5;

        // 用于记录被按下的对象
        var pressedObj;
        var lastLeftObj;

        // 用于记录按下的点
        var start;

        // 移动端运行时的监听
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
    }
})();