$(function () {

    $('#btnSubmitSave').click(function () {
        var error = 0;
        if (iden != 1) {
            var temp = $('#oldpwd');

            if (temp.val().trim().length < 6)
            {
                temp.css('border-color', 'red');
                $("#div_tips1").css("color", "red").text("请输入6-16位密码！");
                error = 1;
            } else {
                temp.removeAttr('style');
                $("#div_tips1").text("");
            }
        }


        temp = $('#NPwd1');
        if (temp.val().trim().length < 6) {
            temp.css('border-color', 'red');
            $("#div_tips2").css("color", "red").text("请输入6-16位密码！");
            error = 1;

        } else {
            temp.removeAttr('style');
            $("#div_tips2").text("");
        }
        temp = $('#NPwd2');
        if (temp.val().trim().length < 6) {
            temp.css('border-color', 'red');
            $("#div_tips3").css("color", "red").text("请输入6-16位密码！");

            error = 1;

        } else {
            temp.removeAttr('style');
            $("#div_tips3").text("");
        }
        if ($('#NPwd1').val().trim() !== $('#NPwd2').val().trim()) {
            $('#NPwd1').css('border-color', 'red');
            $('#NPwd2').css('border-color', 'red');
            layer.msg('您两次输入的新密码不一致...');
            error = 1;
        }
        if (iden != 1) {
            if ($('#oldpwd').val().trim() === $('#NPwd1').val().trim()) {
                layer.msg('新密码与原密码一致,修改失败...');
                error = 1;
            }
        }

        if (error === 1) {
            return;
        }
        var data;
        if (iden != 1) {
             data = {oldpwd: $('#oldpwd').val().trim(), newpwd: $('#NPwd1').val().trim(), val: iden};

        } else {
             data = {newpwd: $('#NPwd1').val().trim(), val: iden};
        }

        $.post('', data, function (res) {

            layer.msg(res.msg);
            setTimeout(function(){

                location.href=my_safety_url;

            },1500);


        });
    });

});
