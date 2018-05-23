function AsyncFunc(page) {
    data = {page: page};
    $.post('', data, function (res) {

        if (page === 1) {
            if (res.length === 0) {
                $('#resdata').html('');
                $('#load_more').parent().remove();
                $("#resdata").append("<li id='msg'>暂无数据</li>");
                return;
            } else {

                $('#resdata').html('');
                for (var i = 0, max = res.length; i < max; i++) {

                    $('#resdata').append('<li><span class="u-name">\
                                        <a target="_blank" href="'+shopping+'&id='+res[i].userid+'">' + res[i].nickname + '</a>\
                                        </span><span class="u-time">' + res[i].creatdate + '</span>\
                                        <span class="u-flag">\
                                ' + (String(res[i].name) === "ZC" ? "注册一元云团帐号" : "参与一元云团") + '</span></li>');
                }
                $('#load_more').parent().remove();
                if (res.length === 10) {
                    $("#ul_invitedlist").append('<li class="ddloca_more"><a id="load_more" href="javascript:AsyncFunc(' + (++page) + ');"\n\
                             style="font-size: 16px;color: lightslategray;">加载更多...</a></li>');
                }

            }
        } else {
            if (res.length === 0 && $('#msg').text() === '') {
                $('#load_more').removeAttr('href');
                $('#load_more').text('没有更多...');
                return;
            }
            for (var i = 0, max = res.length; i < max; i++) {

                $('#resdata').append('<li><span class="u-name">\
                                        <a target="_blank" href="'+shopping+'&id='+res[i].userid+'">' + res[i].nickname + '</a>\
                                        </span><span class="u-time">' + res[i].creatdate + '</span>\
                                        <span class="u-flag">\
                                ' + (String(res[i].name) === "ZC" ? "注册一元云团帐号" : "参与一元云团") + '</span></li>');
            }
            $('#load_more').removeAttr('href');
            $('#load_more').attr("href", "javascript:AsyncFunc(" + (++page) + ");");
        }

    });
}