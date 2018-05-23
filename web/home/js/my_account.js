var data = {};
function GetDataButDate(pageindex) {

    if ($("#startdate").val().trim() !== '' && $("#enddate").val().trim() !== '') {
        data.start = $("#startdate").val();
        data.end = $("#enddate").val();
        data.type = $("#ul_state a[class='z-checked']").attr('state');
        data.cond = undefined;

        $('#ul_region').children('li').children('a').removeClass();
        AsyncFunc(pageindex);
    } else {
        document.getElementById("errormsg").style.display = "block";
    }


}
/*
 * 业务逻辑控制器     
 */
function LogicalController(pageindex) {


    if (!$("#ul_state a[class='z-checked']").attr('state'))
        return;
    if (!$("#ul_region a[class='z-checked']").attr('region'))
        return;
    $("#startdate").val('');
    $("#enddate").val('');
    data.start = undefined;
    data.end = undefined;
    data.cond = $("#ul_region a[class='z-checked']").attr('region');
    data.type = $("#ul_state a[class='z-checked']").attr('state');

    AsyncFunc(pageindex);
}
/*
 * 异步函数
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

                    $("#msginfo").append('<dd><span class="u-commodity-pic">' + new Date(parseInt(res[i]['creat_date']) * 1000).toLocaleString() + '</span>\n\
                                            <span class="u-select-con">' + (parseInt(res[i]['order_type']) === 0 ? "消费" : "充值") + '</span>'
                            + IsPay(res[i]['order_type'], res[i]['cost']) + '</dd>');

                }
                $('#load_more').parent().remove();
                if (res.length === 10) {
                    $("#dl_userbalance").append('<dd style="text-align: center;"><a id="load_more" href="javascript:AsyncFunc(' + (pageindex + 1) + ');"\n\
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

                $("#msginfo").append('<dd><span class="u-commodity-pic">' + new Date(parseInt(res[i]['creat_date']) * 1000).toLocaleString() + '</span>\n\
                                        <span class="u-select-con">' + (parseInt(res[i]['order_type']) === 0 ? "消费" : "充值") + '</span>'
                        + IsPay(res[i]['order_type'], res[i]['cost']) + '</dd>');

            }
            $('#load_more').removeAttr('href');
            $('#load_more').attr("href", "javascript:AsyncFunc(" + (pageindex + 1) + ");");
        }

    });
}
function IsPay(order_type, val) {

    if (parseInt(order_type) === 0) {
        return "<span class='u-commodity-name orange'>-&yen;" + val + "</span><span class='u-operation'>云团商品</span>";
    } else {
        return "<span class='u-commodity-name green'>+&yen;" + val + "</span><span class='u-operation'>网银充值</span>";
    }

}