
function templatefun(type, res) {

    switch (parseInt(type)) {
        case 1:
            switch (parseInt(res.status)) {
                case 1:

                    return '<li><a href="' + announced_detail + '&id=' + res.pid + '" target="_blank" class="g-pic"><img width="100"\
                                                                                                                   height="100"\
                                                                                                                   src="/t/?src=' + res.image + '" alt=""/>\
                                                    ' + (parseInt(res.limit_buy) > 0 ? "<em class=\"z-xg-tips\">限购</em>" : "") + '<span\
                                                                                                                   class="g-bg g-end"></span><span class="g-txt">已揭晓</span></a>\
                                                <div class="g-info">\
                                                    <h2 class="g-title"><a href="' + announced_detail + '&id=' + res.pid + '" target="_blank"\
                                                                           rel="nofollow">(第' + res.qishu + '云)' + res.title + '</a></h2>\
                                                    <p class="g-price">价值：￥' + res.originprice + '.00</p>\
                                                    <div class="g-older">\
                                                        <p>获得者：<a target="_blank" href="' + shopping + '&id=' + res.winuserid + '">' + res.nickname + '</a></p>\
                                                        <p>揭晓时间：' + res.disclosedate + '</p>\
                                                    </div>\
                                                </div>\
                                                <div class="g-total">\
                                                    参与&nbsp;\
                                                    <span class="orange">' + res.nums + '</span>&nbsp;人次\
                                                </div>\
                                                <a href="' + announced_detail + '&id=' + res.pid + '" target="_blank" class="g-see">查看详情</a><i\
                                                    class="single"><i class="single"></i></i>\
                                                <div class="g-time">\
                                                    <div class="aricle">\
                                                        <div class="cir"></div>\
                                                    </div>\
                                                    <div class="time-str">\
                                                        <div class="str">\
                                                            ' + res.creat_date + '\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                                <div class="clear"></div>\
                                            </li>';

                case 2:
                    return '<li><a href="' + announcing_detail2 + '&id=' + res.pid + '&id=' + res.pid + '" target="_blank" class="g-pic">\
                                         <img width="100" height="100" src="/t/?src=' + res.image + '" alt=""/>' + (parseInt(res.limit_buy) > 0 ? "<em class=\"z-xg-tips\">限购</em>" : "") + '</a>\
                                                <div class="g-info">\
                                                    <h2 class="g-title"><a href="' + announcing_detail2 + '&id=' + res.pid + '" rel="nofollow" target="_blank">(第' + res.qishu + '云)' + res.title + '</a></h2>\
                                                    <p class="g-price">价值：' + res.originprice + '.00</p>\
                                                    <a class="code-ing" href="' + announcing_detail2 + '&id=' + res.pid + '" target="_blank" rel="nofollow">正在揭晓<span class="dotting"></span></a>\
                                                </div>\
                                                <div class="g-total">\
                                                    参与&nbsp;\<span class="orange">' + res.nums + '</span>&nbsp;人次</div><a href="' + announcing_detail2 + '&id=' + res.pid + '" target="_blank" rel="nofollow"\
                                                     class="g-buy">我要围观</a><i class="single"><i class="single"></i></i><div class="g-time"><div class="aricle"><div class="cir"></div>\n\
                                                </div><div class="time-str"><div class="str">' + res.creat_date + '</div></div></div><div class="clear"></div></li>';

                default:
                    return '<li><a href="'+announcing_detail1+'&id='+res.id+'" target="_blank" class="g-pic"><img width="100"\
                                                                                                                 height="100"\
                                                                                                                 src="/t/?src=' + res.image + '"\
                                                                                                                 alt=""/><span\
                                                                                                                 class="g-bg"></span><span class="g-txt">进行中<span\
                                                            class="dotting"></span></span>' + (parseInt(res.limit_buy) > 0 ? "<em class=\"z-xg-tips\">限购</em>" : "") + '</a>\
                                                <div class="g-info">\
                                                    <h2 class="g-title"><a href="'+announcing_detail1+'&id='+res.id+'" rel="nofollow"\
                                                                           target="_blank">(第' + res.qishu + '云)' + res.title + '</a></h2>\
                                                    <p class="g-price">价值：￥' + res.originprice + '.00</p>\
                                                    <div class="g-progress">\
                                                        <dl class="m-progress">\
                                                            <dt title="已完成' + parseInt(res.canyushu) / parseInt(res.fenshu) * 100 + '%">\
                                                                <b style="width: ' + parseInt(res.canyushu) / parseInt(res.fenshu) * 100 + '%"><i class="cur"></i></b>\
                                                            </dt>\
                                                            <dd>\
                                                                <span class="orange fl"><em>' + res.canyushu + '</em>已参与</span>\
                                                                <span class="gray6 fl"><em>' + res.fenshu + '</em>总需人次</span>\
                                                                <span class="blue fr"><em>' + (parseInt(res.fenshu) - parseInt(res.canyushu)) + '</em>剩余</span>\
                                                            </dd>\
                                                        </dl>\
                                                    </div>\
                                                </div>\
                                                <div class="g-total">\
                                                    参与&nbsp;\
                                                    <span class="orange">' + res.nums + '</span>&nbsp;人次\
                                                </div>\
                                                <a href="'+announcing_detail1+'&id='+res.id+'" target="_blank" rel="nofollow"\
                                                   class="g-buy">跟随云团</a><i class="single"><i class="single"></i></i>\
                                                <div class="g-time">\
                                                    <div class="aricle">\
                                                        <div class="cir"></div>\
                                                    </div>\
                                                    <div class="time-str">\
                                                        <div class="str">\
                                                            ' + res.creat_date + '\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                                <div class="clear"></div>\
                                            </li>';

            }

        case 2:
            return '<li><a href="' + announced_detail + '&id=' + res.pid + '" target="_blank" class="g-pic"><img width="100"\
                                                                                                                        height="100"\
                                                                                                                        src="/t/?src=' + res.image + '"\
                                                                                                                        alt=""/>' + (parseInt(res.limit_buy) > 0 ? "<em class=\"z-xg-tips\">限购</em>" : "") + '</a>\
                                                <div class="g-info">\
                                                    <h2 class="g-title owner"><a href="' + announced_detail + '&id=' + res.pid + '" target="_blank"\
                                                                                 rel="nofollow">(第' + res.qishu + '云)' + res.title + '</a></h2>\
                                                    <div class="g-older">\
                                                        <p class="g-price">价值：￥' + res.originprice + '.00</p>\
                                                        <p>幸运云购码：<b class="orange">' + res.winningcode + '</b></p>\
                                                    </div>\
                                                </div>\
                                                <div class="g-total">\
                                                    参与&nbsp;\
                                                    <span class="orange">' + res.nums + '</span>&nbsp;人次\
                                                </div>\
                                                <a href="' + announced_detail + '&id=' + res.pid + '" target="_blank" class="g-see" rel="nofollow">查看详情</a><i\
                                                    class="single"><i class="single"></i></i>\
                                                <div class="g-time">\
                                                    <div class="aricle">\
                                                        <div class="cir"></div>\
                                                    </div>\
                                                    <div class="time-str">\
                                                        <div class="str">\
                                                            ' + res.disclosedate + '\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                                <div class="clear"></div>\
                                            </li>';

        default:
            return '<li><h3 class="s-title"><a href="' + share_detail + '&id=' + res.pid + '" target="_blank">' + res.title + '</a></h3>\
                                                <p class="s-info"><a href="' + share_detail + '&id=' + res.pid + '" target="_blank" rel="nofollow">' + res.content + '</a>\
                                                </p>\
                                                <div class="pic-list-wrap">\
                                                    <ol class="pic-list">\
                                                        <li ><span><img width="71px" height="71px"\
                                                                                                   src="/t/?src=' + res.pic1 + '"/></span>\
                                                            <div style="display:none;" class="pic-hover transparent-png"></div>\
                                                        </li>\
                                                        <li ><span><img width="71px" height="71px"\
                                                                                                   src="/t/?src=' + res.pic2 + '"/></span>\
                                                            <div style="display: none;" class="pic-hover transparent-png"></div>\
                                                        </li>\
                                                        <li ><span><img width="71px" height="71px"\
                                                                                                   src="/t/?src=' + res.pic2 + '"/></span>\
                                                            <div style="display:none;" class="pic-hover transparent-png"></div>\
                                                        </li>\
                                                    </ol>\
                                                </div>\
                                                <i class="single"><i class="single"></i></i>\
                                                <div class="g-time">\
                                                    <div class="aricle">\
                                                        <div class="cir"></div>\
                                                    </div>\
                                                    <div class="time-str">\
                                                        <div class="str">\
                                                            ' + res.creatdate + '\
                                                        </div>\
                                                    </div>\
                                                </div>\
                                                <div class="clear"></div>\
                                            </li>';
    }

}
//购买记录分页调用函数
function buyasyncdata(pageindex) {

    $.post('', {type: 1, page: pageindex}, function (res) {
        $("#div_BuyList .good-list").html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $("#div_BuyList .good-list").append(templatefun(res.type, res.items[i]));
        }

    });
}
//中奖纪录分页调用函数
function acqasyncdata(pageindex) {

    $.post('', {type: 2, page: pageindex}, function (res) {
        $("#div_OrderList .good-list").html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $("#div_OrderList .good-list").append(templatefun(res.type, res.items[i]));
        }

    });
}
//晒单分页调用函数
function sunasyncdata(pageindex) {

    $.post('', {type: 3, page: pageindex}, function (res) {
        $("ul[class='good-list good-share']").html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $("ul[class='good-list good-share']").append(templatefun(res.type, res.items[i]));
        }

    });
}
//云购记录信息创建主函数
function cloud_records() {

    $.post('', {type: 1, page: 1}, function (res) {
        $("#div_BuyList .good-list").html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $("#div_BuyList .good-list").append(templatefun(res.type, res.items[i]));
        }
        if (res.count !== 0) {
            $('.cloud-pagination').createPage({
                pageCount: res.count,
                current: 1,
                backFn: function (page) {
                    buyasyncdata(page);
                }
            });
        } else {
            $('#div_BuyList').children('.yg-null-retips-wrapper').css("display","block");
        }

    });

}
//中奖纪录信息创建主函数
function acquired_goods() {

    $.post('', {type: 2, page: 1}, function (res) {
        $("#div_OrderList .good-list").html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $("#div_OrderList .good-list").append(templatefun(res.type, res.items[i]));
        }
        if (res.count !== 0) {
            $('.acquired-pagination').createPage({
                pageCount: res.count,
                current: 1,
                backFn: function (page) {
                    acqasyncdata(page);
                }
            });
        } else {
            $('#div_OrderList').children('.hd-null-retips-wrapper').css("display","block");
        }
    });
}
//晒单记录信息创建主函数
function the_sun() {

    $.post('', {type: 3, page: 1}, function (res) {
        $("ul[class='good-list good-share']").html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $("ul[class='good-list good-share']").append(templatefun(res.type, res.items[i]));
        }
        if (res.count !== 0) {
            $('.sun-pagination').createPage({
                pageCount: res.count,
                current: 1,
                backFn: function (page) {
                    sunasyncdata(page);
                }
            });
        } else {
            $('#div_PostList').children('.sun-null-retips-wrapper').css("display","block");
        }
    });
}
