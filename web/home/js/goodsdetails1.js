

function _asyc_url_buy() {

    $.post(_asyc_url_, {goodsid: id, qishu: qishu, type: 1}, function (res) {
        $('#buy_list_div').html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $('#buy_list_div').append('<li class="">\
                            <span class="time">' + res.items[i]['microtime'] + '</span>\
                            <span class="name">\
                                <span class="w">\
                                    <a title="' + res.items[i]['nickname'] + '" target="_blank" href="#">\
                                        <i class="head-s-img">\
                                            <img style="margin-bottom: 5px;" width="22" height="22" src="/t/?w=200&h=200&src=' + res.items[i]['facepic'] + '" />\
                                        </i>\
                                        ' + res.items[i]['nickname'] + '\
                                    </a>\
                                </span>\
                            </span>\
                            <span class="people">\
                                ' + res.items[i]['nums'] + '\
                                <a href="javascript:;" buynum="3" buyid="207815806" name="showCode">\
                                    查看云团码\
                                </a>\
                            </span>\
                            <!--\
                            <span class="ip">\
                                ' + (res.items[i]['area'] == null ? '' : res.items[i]['area']) + ' IP:' + (res.items[i]['ip'] == null ? '' : res.items[i]['ip']) + '\
                            </span>\
                            <span class="form">\
                                <a href="javascript:;">\
                                    PC电脑\
                                    <i class="f-icon pc">\
                                    </i>\
                                </a>\
                            </span>\
                            -->\
                        </li>');
        }
        if (res.items.length > 0) {
            $('#divPage').createPage({
                pageCount: res.pages,
                current: 1,
                backFn: function (page) {
                    buyasyncdata(page);
                }
            });
        }


    });
}

function buyasyncdata(page) {
    $.post(_asyc_url_, {goodsid: id, qishu: qishu, page: page, type: 1}, function (res) {
        $('#buy_list_div').html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $('#buy_list_div').append('<li class="">\
                            <span class="time">' + res.items[i]['microtime'] + '</span>\
                            <span class="name">\
                                <span class="w">\
                                    <a title="' + res.items[i]['nickname'] + '" target="_blank" href="#">\
                                        <i class="head-s-img">\
                                            <img style="margin-bottom: 5px;" width="22" height="22" src="/t/?w=200&h=200&src=' + res.items[i]['facepic'] + '" />\
                                        </i>\
                                        ' + res.items[i]['nickname'] + '\
                                    </a>\
                                </span>\
                            </span>\
                            <span class="people">\
                                ' + res.items[i]['nums'] + '\
                                <a href="javascript:;" buynum="3" buyid="207815806" name="showCode">\
                                    查看云团码\
                                </a>\
                            </span>\
                            <!--\
                            <span class="ip">\
                                ' + (res.items[i]['area'] == null ? '' : res.items[i]['area']) + ' IP:' + (res.items[i]['ip'] == null ? '' : res.items[i]['ip']) + '\
                            </span>\
                            <span class="form">\
                                <a href="javascript:;">\
                                    PC电脑\
                                    <i class="f-icon pc">\
                                    </i>\
                                </a>\
                            </span>\
                            -->\
                        </li>');
        }
    });
}
function _asyc_url_sun() {

    $.post(_asyc_url_, {goodsid: id, type: 2}, function (res) {
        $('.ng-share-wrapper').html('');
        if (res.items.length === 0) {
            $('.ng-share-wrapper').append('<div class="total">本商品暂无晒单...</div>');
            return;
        }
        for (var i = 0, max = res.items.length; i < max; i++) {
            $('.ng-share-wrapper').append('<div class="ng-share-detail clearfix">\
                        <div class="ng-share-pic">\
                            <a target="_blank" uweb="1010283325" type="showCard" href="#"><img width="80" height="80" alt="" src="/t/?w=600&h=600&src=' + res.items[i]['facepic'] + '" /></a>\
                        </div>\
                        <div class="ng-share-con">\
                            <div class="name-line">\
                                <a class="u-name" rel="nofollow" target="_blank" href="#">' + res.items[i]['nickname'] + '</a>\
                                <span class="u-time">' + res.items[i]['creatdate'] + '</span>\
                            </div>\
                            <div class="u-data">\
                                <span class="u-num">第<span>' + res.items[i]['qishu'] + '</span>云晒单</span>\
                                <a target="_blank" class="u-show" href="#">' + res.items[i]['title'] + '</a>\
                            </div>\
                            <div class="share-info">\
                                <p><a target="_blank" href="#">' + res.items[i]['content'] + '</a></p>\
                            </div>\
                            <div class="pic-list-wrap">\
                                <ul class="pic-list clearfix">\
                                    <li pic="20151112145437294.jpg"><span><img width="71px" height="71px" src="/t/?w=600&h=600&src=' + res.items[i]['pic1'] + '" /></span>\
                                        <div style="display:none;" class="pic-hover transparent-png"></div></li>\
                                    <li pic="20151112145437294.jpg"><span><img width="71px" height="71px" src="/t/?w=600&h=600&src=' + res.items[i]['pic2'] + '" /></span>\
                                        <div style="display:none;" class="pic-hover transparent-png"></div></li>\
                                    <li pic="20151112145437294.jpg"><span><img width="71px" height="71px" src="/t/?w=600&h=600&src=' + res.items[i]['pic3'] + '" /></span>\
                                        <div style="display:none;" class="pic-hover transparent-png"></div></li>\
\
                                </ul>\
                                <!--\
                                <div class="talk-wrap">\
                                    <a class="xianmu" postid="98114" num="4" href="javascript:;"><i class="transparent-png"></i>羡慕(<em>4</em>)<img src="__PCP__/images/xin.png" class="transparent-png" /></a>\
                                    <a class="pinglun" target="_blank" href="#" rel="nofollow"><i class="transparent-png"></i>评论(<em>30</em>)</a>\
                                </div>\
                        -->\
                            </div>\
                        </div>\
                        <div class="clear"></div>\
                    </div>');
        }
        if (res.items.length > 0) {
            $('#divPage1').createPage({
                pageCount: res.pages,
                current: 1,
                backFn: function (page) {
                    buyasyncsun(page);
                }
            });
        }


    });
}

function buyasyncsun(page) {
    $.post(_asyc_url_, {goodsid: id, page: page, type: 2}, function (res) {
        $('.ng-share-wrapper').html('');
        for (var i = 0, max = res.items.length; i < max; i++) {
            $('.ng-share-wrapper').append('<div class="ng-share-detail clearfix">\
                        <div class="ng-share-pic">\
                            <a target="_blank" uweb="1010283325" type="showCard" href="#"><img width="80" height="80" alt="" src="/t/?w=600&h=600&src=' + res.items[i]['facepic'] + '" /></a>\
                        </div>\
                        <div class="ng-share-con">\
                            <div class="name-line">\
                                <a class="u-name" rel="nofollow" target="_blank" href="#">' + res.items[i]['nickname'] + '</a>\
                                <span class="u-time">' + res.items[i]['creatdate'] + '</span>\
                            </div>\
                            <div class="u-data">\
                                <span class="u-num">第<span>' + res.items[i]['qishu'] + '</span>云晒单</span>\
                                <a target="_blank" class="u-show" href="#">' + res.items[i]['title'] + '</a>\
                            </div>\
                            <div class="share-info">\
                                <p><a target="_blank" href="#">' + res.items[i]['content'] + '</a></p>\
                            </div>\
                            <div class="pic-list-wrap">\
                                <ul class="pic-list clearfix">\
                                    <li pic="20151112145437294.jpg"><span><img width="71px" height="71px" src="/t/?w=600&h=600&src=' + res.items[i]['pic1'] + '" /></span>\
                                        <div style="display:none;" class="pic-hover transparent-png"></div></li>\
                                    <li pic="20151112145437294.jpg"><span><img width="71px" height="71px" src="/t/?w=600&h=600&src=' + res.items[i]['pic2'] + '" /></span>\
                                        <div style="display:none;" class="pic-hover transparent-png"></div></li>\
                                    <li pic="20151112145437294.jpg"><span><img width="71px" height="71px" src="/t/?w=600&h=600&src=' + res.items[i]['pic3'] + '" /></span>\
                                        <div style="display:none;" class="pic-hover transparent-png"></div></li>\
\
                                </ul>\
                                <div class="talk-wrap">\
                                    <a class="xianmu" postid="98114" num="4" href="javascript:;"><i class="transparent-png"></i>羡慕(<em>4</em>)<img src="__PCP__/images/xin.png" class="transparent-png" /></a>\
                                    <a class="pinglun" target="_blank" href="#" rel="nofollow"><i class="transparent-png"></i>评论(<em>30</em>)</a>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="clear"></div>\
                    </div>');
        }
    });
}
function cloudlist() {

    $.post(_asyc_url_, {goodsid: id}, function (res) {
        $('#cloudlistdetail').html('');
        for (var i = 0, max = res.length; i < max; i++) {

            switch (parseInt(res[i]['status']))
            {
                case 1:
                    $('#cloudlistdetail').append('<li><a href="' + anno__detai__url + '&id=' + res[i]['goodsid'] + '&qishu=' + res[i]['qishu'] + '" \
     title="第' + res[i]['qishu'] + '云已揭晓" class="orange">第' + res[i]['qishu'] + '云<b>已揭晓<span class="dotting">\
             </span></b></a></li>');
                    break;
                case 2:
                    $('#cloudlistdetail').append('<li><a href="' + anno__detai__url + '&id=' + res[i]['goodsid'] + '&qishu=' + res[i]['qishu'] + '" \
     title="第' + res[i]['qishu'] + '云正在揭晓" class="orange">第' + res[i]['qishu'] + '云<b>正在揭晓<span class="dotting">\
             </span></b></a></li>');
                    break;
                default:
                    $('#cloudlistdetail').append('<li><a href="' + anno__detai__url + '&id=' + res[i]['goodsid'] + '&qishu=' + res[i]['qishu'] + '" \
     title="第' + res[i]['qishu'] + '云进行中" class="orange">第' + res[i]['qishu'] + '云<b>进行中<span class="dotting">\
             </span></b></a></li>');
            }


        }

    });
}

function gotothis() {
    var goqishu = $('.inp > input').val().trim();
    if (goqishu) {
        location = thispageurl + "&id="+id+"&qishu=" + goqishu;
    } else {
        layer.msg('请录入期数关键字...');
    }
}