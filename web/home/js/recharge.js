
$(function () {

    var total = sumTotal();
    var balance = $('#balance').val();

    //alert('total:' + total + 'b:' + balance);

    if (total > parseFloat(balance)) {
        var pay = total - balance;
        $('#iBankPay').html('￥' + formateMoney(pay));

        $("input[name='checkbox1']").click(function () {
            if (!$(this).prop('checked')) {
                $('#iBankPay').html('￥' + formateMoney(total));
            } else {
                $('#iBankPay').html('￥' + formateMoney(pay));
            }
        })
    }

    if (total <= parseFloat(balance)) {
        $('#divBankBox').css('display', 'none');
        $('#spPayTitle').css('display', 'none');

        $("input[name='checkbox1']").click(function () {
            if (!$(this).prop('checked')) {
                $('#divBankBox').css('display', 'block');
                $('#iBankPay').html('￥' + formateMoney(total));
            } else {
                $('#divBankBox').css('display', 'none');
            }
        })
    }


    $('#ul_menu li').click(function () {

        $(this).addClass('curr').siblings().removeClass('curr');
        var i = $(this).index();
        $('.f-pay-bank').eq(i).css('display', 'block');
        $('.f-pay-bank').eq(i).siblings('.f-pay-bank').css('display', 'none');
    })


    $('.g-obtain-title ul li').click(function () {
        var i = $(this).index();
        $('.sidebar_main').eq(i).css('display', 'block');
        $('.sidebar_main').eq(i).siblings('.sidebar_main').css('display', 'none');
    })

})

function formateMoney(m) {

    var ms = m.toString();
    if (ms.indexOf('.') < 0) {
        ms = ms + ".00"
    }
    return ms;
}


function sumTotal() {
    var total = 0;
    $('input.totalPrice').each(function () {
        total += parseFloat($(this).val());
    })

    total = Math.round(total * 100) / 100;
    var t = total.toString();

    if (t.indexOf('.') < 0) {
        t = t + ".00"
    }

    $('#payTotal').html('￥' + t);

    return total;
}




$(function () {
    $('.f-pay-bank dl dd').click(function () {

        // $(this).addClass('checked').siblings().removeClass('checked');

    });
    $('#ulMoneyList li').click(function () {

        $(this).addClass('f-checked').siblings().removeClass('f-checked');
    })
})



$(function () {
    $('#divBankList cite span').click(function () {
        $(this).addClass('current').siblings().removeClass('current');
        var i = $(this).index();
        $('#dlPayForm,#dlCXK,#dlXYK').eq(i).css('display', 'block');
        $('#dlPayForm,#dlCXK,#dlXYK').eq(i).siblings('#dlPayForm,#dlCXK,#dlXYK').css('display', 'none');
    })
})


/**
 *
 * @param {type} obj
 * @returns {undefined}
 */
function setvalue(obj) {

    if (/\d+/.test($(obj).parent('li').attr('money'))) {
        $('#b_money').text(parseFloat($(obj).parent('li').attr('money')));
        $('#hidMoney').val(parseFloat($(obj).parent('li').attr('money')));

    } else {
        $('#b_money').text(0);
        $('#hidMoney').val(0);
    }
}
function setvalues(obj) {
    if (/\d+/.test($(obj).val().trim())) {
        if (parseFloat($(obj).val()) === 0) {
            $('.orange').html('￥0.00');

        } else if (parseFloat($(obj).val()) < 1) {
            $('.orange').html('￥' + parseFloat($(obj).val()));
        } else {
            $('#b_money').text(parseFloat($(obj).val()));
        }
        $('#hidMoney').val(parseFloat($(obj).val()));

    } else {
        $('#b_money').text(0);
        $('#hidMoney').val(0);
    }
}

function checkmoney() {
    if (parseFloat($('#hidMoney').val().trim()) <= 0.01) {
        layer.msg('您的充值金额小于0.01,无法继续提交...');
        return false;
    } else {
        return true;
    }
}
function confirm() {
    var card = $('#txtCard').val();
    var pwd = $('#txtPwd').val();
    if (card.length < 12) {
        layer.msg('您输入卡号不足12位,请重新输入');
        return;
    }
    if (pwd.length < 8) {
        layer.msg('您输入密码不足8位,请重新输入');
        return;
    }


    $.post(actionurl, {cardid: card, pass: pwd}, function (msg) {

        if (!msg['status']) {
            layer.msg('卡号或密码错误,查询卡信息失败!');
            return;
        }
        if (msg['status'] == 0) {
            $('#liMsg').html('');
            $('#liMsg').html("<span style='vertical-align:text-top;font-size: 18px;'>\n\
                    *</span>&nbsp;&nbsp;该充值卡已被禁用,如有疑问,请联系1元云团官方客服热线:400-800-0731(工作时间9:00-21:00)");
            return;
        }
        if (parseInt(msg['paydate']) != 0) {
            $('#liMsg').html('');
            $('#liMsg').html("<span style='vertical-align:text-top;font-size: 18px;'>\n\
                    *</span>&nbsp;&nbsp;该充值卡已于" + new Date(parseInt(msg['paydate']) * 1000).toLocaleString() + "使用,如有疑问,请联系1元云团官方客服热线:400-800-0731(工作时间9:00-21:00)");
            return;
        }
        if (msg['overduedate'] < msg['nowDate']) {
            $('#liMsg').html('');
            $('#liMsg').html("<span style='vertical-align:text-top;font-size: 18px;'>\n\
                    *</span>&nbsp;&nbsp;该充值卡已于" + new Date(parseInt(msg['overduedate']) * 1000).toLocaleString() + "过期,如有疑问,请联系1元云团官方客服热线:400-800-0731(工作时间9:00-21:00)");
            return;
        }
        layer.confirm('您使用的充值卡面额为' + msg['paycardsize'] + '元,确定要充值吗?', {
            btn: ['确定', '取消']
        }, function () {

            $.post('', {cardid: card, pass: pwd}, function (msg) {
                if (msg) {
                    layer.msg('已成功为您的账户充值!');
                    setTimeout(function(){
                        location.href=my_account;
                    },1000);
                    return;
                } else {

                    layer.msg('系统出错,充值失败!');
                    return;
                }
            }, 'json');
        }
        );

    }, 'json');
}
