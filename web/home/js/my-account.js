
$(function () {
    $("#ul_state li,#ul_region li").click(function () {
        $(this).children('a').addClass("z-checked");
        $(this).siblings().children('a').removeClass("z-checked");
    })

    $(".gray6,.select-state").hover(function () {
        $(this).parent('').addClass("state-hover").siblings().removeClass('state-hover');

    }, function () {
        $(this).parent('').removeClass('state-hover').siblings().removeClass('state-hover');

    });
    $("input.mh_date").manhuaDate({
        Event: "click", //可选               
        Left: 0, //弹出时间停靠的左边位置
        Top: -16, //弹出时间停靠的顶部边位置
        fuhao: "-", //日期连接符默认为-
        isTime: false, //是否开启时间值默认为false
        beginY: 1949, //年份的开始默认为1949
        endY: 2100//年份的结束默认为2049
    });

   
});              