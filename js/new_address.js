(function () {
    "use strict";

    valiLogin();

    var aid = getUrlParams().aid;
    if (aid) {
        $.ajax({
            type: 'POST',
            url: preHttp + 'getEditAddress',
            xhrFields: { withCredentials: true },
            crossDomain: true,
            data: { aid: aid },
            success: function success(res) {
                // console.log(res)
                if (!res) return;
                var res = res[0];
                $("#consignee").val(res.name);
                $("#phone").val(res.phone);
                $("#addressDetail").val(res.address);

                $("#province").find('[value=' + res.province + ']').prop("selected", true);
                var cities = addressDetail['' + res.province],
                    html = "";
                for (var c in cities) {
                    html += '<option value="' + c + '">' + cities[c] + '</option>';
                }
                $("#city").html(html).find('[value=' + res.city + ']').prop("selected", true);

                var areas = addressDetail['' + res.city];
                html = "";
                for (var a in areas) {
                    html += '<option value="' + a + '">' + areas[a] + '</option>';
                }
                $("#district").html(html).find('[value=' + res.area + ']').prop("selected", true);
            },
            error: function error() {
                console.log(123);
            }
        });
    }

    //地址栏下拉选项相关操作
    // 初始化状态
    $("#province").html('<option value=\'0\'>--\u8BF7\u9009\u62E9--</option>');
    $("#city").html('<option value=\'0\'>--\u8BF7\u9009\u62E9--</option>');
    $("#district").html('<option value=\'0\'>--\u8BF7\u9009\u62E9--</option>');

    var html = "";
    // 生成省级选项
    for (var i in addressDetail["100000"]) {
        html += '<option value=\'' + i + '\'>' + addressDetail["100000"][i] + '</option>';
    }

    $("#province").append(html).on("change", function (e) {
        var e = e || window.event;
        var target = e.target || e.srcElement;
        var $this = $(target);
        var pid = $this.children("option:selected").val();

        //切换市级选项
        var cities = addressDetail[pid];
        var a = true,
            citiesid,
            html = "";
        for (var c in cities) {
            html += '<option value="' + c + '">' + cities[c] + '</option>';
            if (a) {
                citiesid = c;a = false;
            }
        }
        $("#city").html(html);

        //生成区级选项
        var html = "";
        for (var area in addressDetail[citiesid]) {
            html += '<option value="' + area + '">' + addressDetail[citiesid][area] + '</option>';
        }
        $("#district").html(html);
    });

    $("#city").on("change", function (e) {
        var e = e || window.event;
        var target = e.target || e.srcElement;

        var $this = $(target),
            cid = $this.children("option:selected").val(),
            city = addressDetail[cid];

        //切换区级选项
        var html = "";
        for (var d in city) {
            html += '<option value="' + d + '">' + city[d] + '</option>';
        }
        $("#district").html(html);
    });

    $("[date-type=address-save]").click(function () {

        var name = $("#consignee").val().replace(/\s/g, '');
        var phone = $("#phone").val().replace(/\s/g, '');
        var addressDetail = $("#addressDetail").val().replace(/\s/g, '');

        var province = $("#province").children("option:selected").val();
        var city = $("#city").children("option:selected").val();
        var district = $("#district").children("option:selected").val();

        //验证非空  弹窗提示
        if (!name || !phone) {
            setPop("请输入完整信息");
        } else if (province == 0) {
            setPop("请选择省、市、区选项");
        } else if (!addressDetail) {
            setPop("请填写详细地址");
        } else {
            var regexp = /^1[3-9]\d{9}$/;
            if (!regexp.test(parseInt(phone))) {
                setPop("手机格式不正确");
                return;
            }
        }

        if (name && phone && province && addressDetail) {
            $.ajax({
                type: 'post',
                url: preHttp + 'newAddress',
                xhrFields: { withCredentials: true },
                crossDomain: true,
                data: { name: name, phone: phone, province: province, city: city, district: district, addressDetail: addressDetail, aid: aid },
                success: function success(result) {
                    console.log(result);
                    if (result.code == 200) {
                        setPop("保存成功");
                        setTimeout(function () {
                            window.history.go(-1);
                        }, 2000);
                    }
                },
                error: function error() {
                    console.log("ajax error");
                }
            });
        }
    });
})();