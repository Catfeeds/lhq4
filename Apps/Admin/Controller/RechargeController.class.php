<?php
namespace Admin\Controller;

/*
 * 充值记录控制器
 */
class RechargeController extends AdminController
{

    function index() // 充值记录首页
    {
        $where = array();
        
        $count = M("recharge")->where($where)->count(); // 总页数
        $page = I('get.p', 1, 'int');
        $show = array(
            'total' => $count,
            'page' => $page,
            'row' => 10,
            'href' => unsetParam("p",  __SELF__) . '&p={{page}}'
        );       
        
        $this->assign("page",$show);
        
        if ($_GET["status"]) {
            $where["status"] = $_GET["status"];
        }
        $recharge = M("recharge")->where($where)->page($page, ROWS)->select();
        $recharge["creatdate"];
        $this->assign("recharge", $recharge);
        
        $this->display();
    }

    function add() // 用户充值
    {
        
        // 获取用户id,并传回模板
        if (IS_GET) {
            $userid = I("get.id");
            
            $user = D("user")->field("nickname")->find($userid);
            $this->assign("username", $user["nickname"]);
            $this->assign("userid", $userid);
        }
        
        if (IS_POST) {
            
            // 接收充值金额
            $jine = (float) I("post.jine");
            // 接收备注
            $more = I("post.more");
            
            // 接收用户id
            $userid = I("post.uid");
            // 金额为数字
            if ($jine != 0) {
                
                $validateRule = array(
                    array( "money",  "number",  "充值金额非法!" )
                );
                
                $autoRule = array(
                    array( "userId", $userid ),
                    array( "rechargeSn", "" ),
                    array( "mode", "" ),
                    array( "creatDate", "time", 3, "function" ),
                    array( "status", "1"  ),
                    array( "money", $jine ),
                    array( "more", $more )
                );
                
                $recharge = M('recharge');
                $recharge->setProperty('_validate', $validateRule);
                $recharge->setProperty('_auto', $autoRule);
                
                $data = $recharge->create();
                // 金额只能为正数
                if ($data["money"] <= 0) {
                    $this->error("金额只能大于 0 ! ");
                    exit();
                }
                
                if (! $data) {
                    $this->error($recharge->getError());
                    exit();
                } // 验证没通过
                
                try {
                    $id = $recharge->add($data); // 添加充值记录
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                
                if (! $id) {
                    $this->error("充值失败! ");
                    exit();
                } // 添加记录失败
                
                $tablename ="member";
                $sql = "UPDATE $tablename SET balance=balance+$jine where member_id=$userid";
                try {
                    if (D()->execute($sql)) { // 修改用户余额
                        
                        $this->success("充值成功,可通过充值记录查看", "javascript:window.close()", 3);
                        exit();
                    } else { // 修改失败, 删除充值记录
                        $recharge->delete($id);
                        $this->error("充值失败! ");
                        exit();
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                $this->error("金额只能为大于0的数字! ");
                exit();
            }
        }
        
        $this->display();
    }
}