var data = {};
var operation;
/**
 * 根据时间获取后台数据
 * @param {type} pageindex
 * @returns {undefined}
 */
function GetDataButDate(page) {

    if ($("#startdate").val().trim() !== '' && $("#enddate").val().trim() !== '') {
        data.type = $("#ul_state a[class='z-checked']").attr('state');
        data.start = $("#startdate").val();
        data.end = $("#enddate").val();
        data.cond = undefined;
        $('#ul_region').children('li').children('a').removeClass();
        AsyncFunc(page);
    }


}
/**
 * 逻辑控制函数
 * @param {type} res
 * @returns {undefined}
 */
function LogicalController(page) {

    if (!$("#ul_state a[class='z-checked']").attr('state'))
        return;
    if (!$("#ul_region a[class='z-checked']").attr('region'))
        return;
    data.type = $("#ul_state a[class='z-checked']").attr('state');
    data.start = undefined;
    data.end = undefined;
    data.cond = $("#ul_region a[class='z-checked']").attr('region');
    $("#startdate").val('');
    $("#enddate").val('');

    AsyncFunc(page);
}



/**
 * 异步获取后台数据
 * @param {type} image
 * @param {type} limit_buy
 * @param {type} title
 * @param {type} qishu
 * @param {type} originprice
 * @param {type} nickname
 * @param {type} order_code
 * @returns {String}
 */
function AsyncFunc(page) {
    data.page = page;
    $.post('', data, function (res) {

        if (page === 1) {
            if (res.items.length === 0) {
                $('#msginfo').html('');
                $('#load_more').parent().remove();
                $("#msginfo").append("<dd id='msg'>暂无数据</dd>");
            } else {

                $('#msginfo').html('');
                for (var i = 0, max = res.items.length; i < max; i++) {

                    restemplate(res.items[i]);
                }
                $('#load_more').parent().remove();

                if (res.items.length === 10) {

                    $("#div_UserBuyList").append('<dd class="ddloca_more"><a id="load_more" href="javascript:AsyncFunc(' + (page + 1) + ');"\n\
                             style="font-size: 16px;color: lightslategray;">加载更多...</a></dd>');
                }

            }
        } else {
            if (res.items.length === 0 && $('#msg').text() === '') {
                $('#load_more').removeAttr('href');
                $('#load_more').text('没有更多...');
                return;
            }
            for (var i = 0, max = res.items.length; i < max; i++) {

                restemplate(res.items[i]);
            }
            $('#load_more').removeAttr('href');
            $('#load_more').attr("href", "javascript:AsyncFunc(" + (page + 1) + ");");
        }

    });
}

/**
 * js模板输出
 * @param {type} res
 * @returns {undefined}
 */
function restemplate(res) {


    switch (parseInt(res.status)) {

        case 1:
            operation = '已揭晓';
            break;
        case 2:
            operation = '正在揭晓';
            break;
        default:
            operation = '进行中';
            ;
    }


    $("#msginfo").append('<dd class="has-announced">\
                                    <span class="u-commodity-pic"><a target="_blank" href="' + res.url + '"><img src="/t/?src=' + res.image + '" /><i class="u-personal"></i></a>' + (parseInt(res.limit_buy) !== 0 ? "<em class='z-xg-tips'>限购</em>" : "") + '</span>\
                                    <span class="u-commodity-name gray9"><h3><a target="_blank" href="' + res.url + '" class="gray3">(第' + res.qishu + '云)' + res.title + '</a></h3><em>价值：&yen;' + res.originprice + '</em>获得者：'+(res.nickname===undefined?"<a href='javascript:;' class='z-winner-gray' target='_blank'>暂未揭晓</a>":"<a href="+shopping+"&id="+res.winuserid+" class='z-winner-gray' target='_blank'>"+res.nickname+"</a>")+'</span>\
                                    <span class="u-select-con"><a href="javascript:;" class="gray9">' + operation + '</a></span>\
                                    <span class="u-buy-num" codeid="1405559" buynum="8"><a href="#" class="gray6">' + res.nums + '人次</a></span>\
                                    <span class="u-operation"><a target="_blank" href="' + my_shoppingdetail + '&id=' + res.id + '&qishu=' + res.qishu + '" class="z-see-details">查看所有云团码</a></span>\
                                </dd> ');


}
