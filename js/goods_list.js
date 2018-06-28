(function () {
    "use strict";

    $(".select_box").on('click', function () {
        $(".mask").show();$("[data-pop=options]").show();
    });
    $(".mask").click(function () {
        $(".mask").hide();$("[data-pop=options]").hide();
    });

    var rule,
        type = getUrlParams().type;

    function loadGoodsList(rule, changeRule, num) {
        //parameter = { 'rule':  , 'changeRule':  , 'num':  }      rule.num 若不定义，后台有处理
        var page = $("#list").attr("data-page");
        $.ajax({
            type: 'GET',
            url: preHttp + 'getgoods',
            data: { type: type, page: page, rule: rule, num: num },
            success: function success(res) {
                // console.log(res)
                if (res.code == 200) {
                    var html = "",
                        list = res.list;
                    var _iteratorNormalCompletion = true;
                    var _didIteratorError = false;
                    var _iteratorError = undefined;

                    try {
                        for (var _iterator = list[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                            var a = _step.value;

                            html += "<li><dl class=\"goods_item flex_between\"><dt> <a href=\"goods_details.html?pid=" + a.id + "\"><img src=\"" + (preLink + a.logo) + "\" alt=\"\"></a></dt><dd class=\"goods_cont\"><h3>" + a.sname + "</h3><p class=\"score\"> <span><i></i>\u79EF\u5206 </span> <span class=\"goods_score\">" + a.score + "</span>";
                            if (a.price && parseInt(a.price) > 0) {
                                html += "+ <span class=\"goods_price\"><b>\uFFE5</b>" + a.price + "</span>";
                            }

                            html += "</p><p>\u5E02\u573A\u53C2\u8003\u4EF7\uFF1A" + a.realprice + "\u5143 </p></dd></dl></li>";
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

                    changeRule ? $("#list").html(html) : setTimeout(function () {
                        $(".load_more").hide();    
                        setTimeout(() => {  $("#list").append(html) }, 300);
                    }, 1000); //判断是加载更多还是更换显示规则

                    $("#list").attr("data-page", res.page).attr("data-totolpage", res.totalPages);
                    expandClick(".goods_list");
                }
            },
            error: function error(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    };

    loadGoodsList();

    $("[data-pop=options]").on("click", 'li', function () {
        if ($(this).children().hasClass('icon_check')) {
            return;
        }

        switch (this) {//rule   排序规则
            case $("[data-pop=options] li:nth-child(1)")[0]:
                rule = 1;   loadGoodsList(1, true);
                break;
            case $("[data-pop=options] li:nth-child(2)")[0]:
                rule = 2;   loadGoodsList(3, true);
                break;
            case $("[data-pop=options] li:nth-child(3)")[0]:
                rule = 3;   loadGoodsList(2, true);
                break;
            case $("[data-pop=options] li:nth-child(4)")[0]:
                rule = 4;   loadGoodsList(4, true);
                break;
        }
        $(this).parent().find('.icon_check').attr('class', "");
        $(this).children().attr('class', "fa fa-check icon_check");
        $(".mask").hide();
        $("[data-pop=options]").hide();
    });

    //上拉加载更多                        默认显示15条数据   加载过多后每次获取 30 条数据
    var isReady = false;
    function refresh(loadmore, refresh) {
        $(window).scroll(function () {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var clientHeight = $(this).height();
            // console.log('正在滑动__'+"top:"+scrollTop+",doc:"+scrollHeight+",client:"+clientHeight);
            if (scrollTop + clientHeight >= scrollHeight) {
                console.log('下拉'); //调用筛选方法，count为当前分页数
                $(".load_more").html("加载更多").show();
                setTimeout(function () {
                    isReady = true;
                }, 10);

                if (scrollTop + clientHeight >= scrollHeight && isReady) {
                    if ($('#list').attr('data-page') == $('#list').attr('data-totolpage') && isReady) {
                        $(".load_more").html("没有更多数据了");
                        return;
                    }
                    $(".load_more").html("<i class='fa fa-spinner fa-pulse fa-lg'></i>&nbsp;&nbsp;正在加载中");
                    console.log("开始加载数据");
                    console.log(rule);
                    loadGoodsList(rule, false, 30);
                }
            } else if (scrollTop <= 0) {
                //滚动条距离顶部的高度小于等于0 TODO
                console.log('上拉');
                if (refresh) {
                    // refresh(); 
                }
            }
        });
    }

    //调用
    refresh();
})();