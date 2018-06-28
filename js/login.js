(function () {
    "use strict";

    function setVerify() {
        var html = "<img id=\"verify\" name=\"verify\" src=\"http://community.73776.com/index.php/shop/WebShop/verify\" onclick=\"this.src='http://community.73776.com/index.php/shop/WebShop/verify?'+new Date().getTime();\" alt=\"\u9A8C\u8BC1\u7801\"/>";
        $(".verify").html(html);
    }
    $("#register-account, #login-account").blur(function () { setVerify() });

    // valiLogin();

    var vali_account = false,
        vali_upwd = false,
        vali_confirm = false;
    //登录
    console.log($("#login"))
    $("#login").click(function (ev) {
        var e = ev || window.event;
        e.preventDefault();
        var $this = $(e.target);
        if ($this.hasClass("active")) {
            //登录操作
            var uname = $("#login-account").val(),
                upwd = $("#login-upwd").val(),
                verify = $("#vali_code").val();
            if (!verify) setPop("请输入验证码");
            if (uname && upwd && verify) {
                var ajaxObj = $.ajax({
                    type: 'post',
                    url: preHttp + 'login',
                    data: { uname: uname, upwd: upwd, verify: verify },
                    xhrFields: { withCredentials: true },
                    crossDomain: true,
                    success: function success(result) {
                        console.log(result);
                        if (result.code == 200) { 
                            setPop("登录成功");
                            localStorage.setItem("zdkjutoken",result.utoken );
                            sessionStorage.setItem("_zdkj_userInfo",JSON.stringify({'uname': result.uname, 'score': result.score}) );

                            if( window.navigator.cookieEnabled ) {
                                setCookie("PHPSESSID", result.phpsessid);    
                                setCookie("_ZDKJCREDENT", result.ucookie, 7);    
                            } else {
                                alert("浏览器未启用cookie,无法自动登录,请重新设置浏览器！")
                            }

                            setTimeout( ()=>{ 
                                console.log(window.history)
                                if ( window.history.state==null ) { window.location.href="index.html"}
                                window.history.go(-1);
                                 
                            },1500 )   //1.5s后跳刷新进入登录前页面
                        } else if (result.code == 201) {
                            setPop("用户名或密码错误","#pop",3500)
                        } else if (result.code == 301) {
                            setPop("验证码错误","#pop",3500)
                            setVerify();
                        }
                    },
                    error: function error() {
                        console.log("未知错误，登录失败");
                    }
                });
            } else {
                setPop("用户名或密码不能为空");
            }
        } else {
            //切换到注册模块
            $this.siblings().removeClass("active");
            $this.addClass("active");
            $("[data-model=register]").hide();
            $("[data-model=login]").show();
        }
    });

    //注册
    $("#register-account").blur(function () {
        var uname = $(this).val();
        if (this.validity.valueMissing) {
            $(this).next().html('用户名不能为空');
        } else if (this.validity.tooShort) {
            $(this).next().html('用户名不能少于3位');
        } else if (this.validity.patternMismatch) {
            $(this).next().html("格式不正确，以字母开头，字母/数字/下划线，3-12位");
        } else {
            //重名验证
            $.ajax({
                type: 'get',
                url: preHttp + 'valiusername',
                xhrFields: { withCredentials: true },
                crossDomain: true,
                data: { uname: uname },
                success: function success(result) {
                    $("#register-account").next().html(""); //先清空
                    if (result.code == 201) {
                        $("#register-account").next().html("用户名已存在");
                    } else {
                        vali_account = true;
                    }
                },
                error: function error() {
                    console.log("异步验证出错");
                }
            });
        }
    });

    $("#register-upwd").blur(function () {
        if (this.validity.valueMissing) {
            $(this).next().html('密码不能为空');
        } else if (this.validity.tooShort) {
            $(this).next().html('密码不能少于6位');
        } else if (this.validity.patternMismatch) {
            $(this).next().html("格式不正确，字母/数字，6-16位");
        } else {
            $(this).next().html("");
            vali_upwd = true;
        }
    });

    $("#register-cpwd").blur(function () {
        var upwd = $(this).val();
        var cpwd = $("#register-upwd").val();
        if (this.validity.valueMissing) {
            $(this).next().html('密码不能为空');
        } else if (upwd != cpwd) {
            $(this).next().html('两次密码不一致');
        } else {
            $(this).next().html("");
            vali_confirm = true;
        }
    });

    $("#register").click(function (e) {
        e.preventDefault();
        var $this = $(e.target),
            uname = $("#register-account").val(),
            upwd = $("#register-upwd").val();
        var verify = $("#vali_code").val();
        if (!verify) setPop("请输入验证码");

        if ($this.hasClass("active")) {
            //注册操作
            var isAdmissibility = vali_account && vali_confirm && vali_upwd;
            if (!isAdmissibility) return;

            if (uname && upwd && verify) {
                $.ajax({
                    type: 'post',
                    url: preHttp + 'register',
                    xhrFields: { withCredentials: true },
                    crossDomain: true,
                    data: { uname: uname, upwd: upwd, verify: verify },
                    success: function success(result) {
                        console.log(result);
                        if (result.code == 200) {
                            setPop("注册成功");
                            setTimeout(function () {
                                window.location.href = "login.html";
                            }, 1500);
                        } else if (result.code == 500) {
                            setPop("注册失败，请稍候重试");
                        } else if (result.code == 301) {
                            setPop("验证码错误");
                        }
                    },
                    error: function error() {
                        setPop("服务器错误，请稍候重试", "#pop", 5000);
                    }
                });
            } else {
                setPop("用户名或密码不能为空", "#pop", 3000);
            }
        } else {
            //切换到登录模块
            $this.siblings().removeClass("active");
            $this.addClass("active");
            $("[data-model=login]").hide();
            $("[data-model=register]").show();
        }
    });
})();