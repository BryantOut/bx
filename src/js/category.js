$(function ($) {
    init();


    function init() {
        setHTML();
        getQsearch();
   }



    // 为了在pc端更好的去调试
    window.onresize = function () {
        setHTML();
    }

    function setHTML() {
        // 基础值
        var baseVal = 100;
        // 设计稿的宽度
        var pageWidth = 375;
        // 要适配的屏幕的宽度?
        var screenWidth = document.querySelector("html").offsetWidth;
        // 要设置的fontsize
        var fontsize = screenWidth * baseVal / pageWidth;

        // 设置到html标签的中
        document.querySelector("html").style.fontSize = fontsize + "px";

    }

    function getQsearch() {
        $.get("categories", function (ret) {
            console.log(ret);
            var data = ret.data;
            //调用模板引擎渲染数据
            var context = {
                comments: data
            }
            //借助模板引擎的api
            var html = template('leftNavTmpl', context);
            //将渲染结果的html设置到默认元素的innerHTML中
            $(".leftNav ul").html(html);

            var myScroll = new IScroll('.leftNav');
        });
    }
})