var data = {};
function GetDataButDate(pageindex) {

    if ($("#startdate").val().trim() !== '' && $("#enddate").val().trim() !== '') {
        data.start = $("#startdate").val();
        data.end = $("#enddate").val();
        data.cond = undefined;
        data.type = $("#ul_state a[class='z-checked']").attr('state');
        $('#ul_region').children('li').children('a').removeClass();
        AsyncFunc(pageindex);
    } else {
        document.getElementById("errormsg").style.display = "block";
    }
}


/**
 * 逻辑控制函数
 * @param {type} res
 * @returns {undefined}
 */
function LogicalController(pageindex) {
    if (!$("#ul_state a[class='z-checked']").attr('state'))
        return;
    if (!$("#ul_region a[class='z-checked']").attr('region'))
        return;
    data.start = undefined;
    data.end = undefined;
    data.cond = $("#ul_region a[class='z-checked']").attr('region');
    data.type = $("#ul_state a[class='z-checked']").attr('state');
    $("#startdate").val('');
    $("#enddate").val('');

    AsyncFunc(pageindex);
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
function AsyncFunc(pageindex) {
    data.page = pageindex;
    $.post('', data, function (res) {

        if (pageindex === 0) {
            if (res.length === 0) {
                $('#msginfo').html('');
                $('#load_more').parent().remove();
                $("#msginfo").append("<dd id='msg'>暂无数据</dd>");
            } else {

                $('#msginfo').html('');

                for (var i = 0, max = res.length; i < max; i++) {

                    restemplate(res[i]);

                }
                $('#load_more').parent().remove();
                if (res.length === 10) {
                    $("#div_OrderList").append('<dd style="text-align: center;"><a id="load_more" href="javascript:AsyncFunc(' + (pageindex + 1) + ');"\n\
                             style="font-size: 16px;padding: 15px;color: slategray;">加载更多...</a></dd>');
                }

            }
        } else {
            if (res.length === 0 && $('#msg').text() === '') {
                $('#load_more').removeAttr('href');
                $('#load_more').text('没有更多...');

                return;
            }
            for (var i = 0, max = res.length; i < max; i++) {

                restemplate(res[i]);

            }
            $('#load_more').removeAttr('href');
            $('#load_more').attr("href", "javascript:AsyncFunc(" + (pageindex + 1) + ");");
        }

    });
}
/**
 * 模板函数
 * @returns {String} 模板字符串
 */
function restemplate(res) {
    var operation = '';

    switch (parseInt(res.flag)) {

        case 1:
            operation = '<a  href = "' + getaward + '&pid=' + res.pid + '"> 领取奖品 </a>';
            break;
        case 2:
            operation = '<a onclick="confirmgs(this,' + res.pid + ');" href = "javascript:;"> 确认收货 </a>';
            break;
        case 3:
            operation = '<a href = "javascript:tosun('+res.pid+');"> 已收货,去晒单? </a>';
            break;
        default:
            operation = '<a href = "javascript:;"> 等待发货 </a>';
            ;
    }
    $("#msginfo").append('<dd class="has-announced"><span class="u-order-number line-height">' + res.order_code + '</span>\
               <span class="u-commodity-pic"><a target="_blank" href="' + announced_detail + '&id=' + res.pid + '">\
<img src="/t/?src=' + res.image + '" /><i class="u-personal">\
</i></a>' + (res.limit_buy !== 0 ? "<em class='z-xg-tips'>限购</em>" : "") + '\
</span><span class="u-commodity-name"><em>价值：&yen;' + res.originprice + '</em>获得者：\
<a href="' + shopping + '&id=' + res.winuserid + '" class="z-winner-gray" target="_blank">' + res.nickname + '\
</a><h3><a target="_blank" \
href="' + announced_detail + '&id=' + res.pid + '" class="gray3">(第' + res.qishu + '云)' + res.title + '\
</a></h3></span>\
<span class="u-operation line-height">' + operation + '</span></dd>');

}


function confirmgs(obj, pid) {

    $.post(confrimgs, {pid: pid}, function (res) {
        if (res.code == 1) {
            layer.msg('确认领奖完成...');
            setTimeout(function () {
                $(obj).parent().parent().hide('slow');
            }, 1000);

        } else {
            layer.msg('确认领奖失败,请稍候重试...');
        }
    });
}

function tosun(pid){
    $.get(checkeurl,{pid:pid},function(res){
      console.log(res);
        if (res>0) {
            layer.msg('您已经晒单了,请不要重复晒单...');
        }else{
            location.href= thesun+'&pid='+pid;
        }
    });

}

$(function () {
    $("#ul_state li,#ul_region li").click(function () {
        $(this).children('a').addClass("z-checked");
        $(this).siblings().children('a').removeClass("z-checked");
    });

    LogicalController(0);

});
