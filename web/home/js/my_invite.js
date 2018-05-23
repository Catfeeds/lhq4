 var operation;
 var data={};
function getdatabydate() {
                    
                    if ($("#startdate").val().trim() !== '' && $("#enddate").val().trim() !== '') {                        
                        data.startdate = $("#startdate").val();
                        data.enddate = $("#enddate").val();
                        data.region = undefined;
                        $('#ul_region').children('li').children('a').removeClass();
                    }
                    $.post('', data, function (res) {
             
                        $("#ul_commissionlist").html('');
                        for (var i = 0, max = res.items.length; i < max; i++) {
                            $("#ul_commissionlist").append(templatefun(res.items[i]));
                        }
                        $('#pagination').createPage({
                            pageCount: res.count,
                            current: 1,
                            backFn: function (page) {
                                asynchronous(page);
                            }
                        });
                    });
                }
                //分页调用函数
                function asynchronous(pageindex) {
                    data.page=pageindex;
                    $.post('', data, function (res) {
                        $("#ul_commissionlist").html('');
                        for (var i = 0, max = res.items.length; i < max; i++) {
                            $("#ul_commissionlist").append(templatefun(res.items[i]));
                        }
                    });
                }
                //邀请记录信息创建主函数
                function consumelist() {
                   
                   
               
                    if ($("#ul_region a[class='z-checked']").attr('region') !== '') {
                        data.region = $("#ul_region a[class='z-checked']").attr('region');
                        $("#startdate").val('');
                        $("#enddate").val('');
                        data.startdate = undefined;
                        data.enddate = undefined;
                       

                    }
                    $.post('', data, function (res) {
                        $("#ul_commissionlist").html('');
                        for (var i = 0, max = res.items.length; i < max; i++) {
                            $("#ul_commissionlist").append(templatefun(res.items[i]));
                        }
                        $('#pagination').createPage({
                            pageCount: res.count,
                            current: 1,
                            backFn: function (page) {
                                asynchronous(page);
                            }
                        });
                    });

                }
                
                function templatefun(res)
                {
                    if (String(res.name) === "XF") {
                        operation = '消费';
                    } else {
                        operation = '注册';
                    }
                    return '<li>\
                                    <span class="u-time">' + res.creatdate + '</span>\
                                    <span class="u-name"><a href="'+shopping+'&id='+res.userid+'" target="_blank">' + res.nickname + '</a></span>\
                                    <span class="u-info">' + operation + '</span>\
                                    <span class="u-much"><span>￥' + res.consume + '.00</span></span>\
                                    <span class="u-money"><strong class="green">+￥' + res.comval + '</strong>\
                                    </span>\
                                </li>';
                }