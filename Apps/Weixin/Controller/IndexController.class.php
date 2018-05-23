<?php

namespace Weixin\Controller;

use Think\Controller;

/**
 * 微信模块的默认控制器
 * @date: 2015年11月14日 下午2:08:00
 * @author: 王崇全
 */
class IndexController extends IsLoginController {

    /**
     * 默认操作方法
     * @date: 2015年11月14日 下午2:09:49
     * @author: 王崇全
     */
    function index() {
        //获取首页轮播图
        $order = array(
            'taxis', 'creatDate' => 'desc'
        );
        $banner = M("Banner")->order($order)->select();
        $this->assign("banner", $banner);

        /**
         * 获取商品详情
         * */
        $order1 = array(); //排序
        $map1 = array(); //查询条件


        $page = I('get.page', 1, 'int');
        $type = I('get.type', 1, 'int');
        $cgsta = I('get.cgsta');
        $show_index = 30;
        $goods = M('Goods');
        $max_page = ceil($goods->count() / $show_index);



        //即将揭晓
        if (IS_AJAX && $type == 1) {
            $goods1 = M("Goods")
                    ->order('canyushu/fenshu desc')
					->where('status = 1')
                    ->page($page, $show_index)
                    ->select();


            foreach ($goods1 as &$v) {
                $v ['url'] = U('Weixin/Goods/product_detail2', 'id=' . $v ['id']);
            }

            json(array(
                'page' => $page,
                'max_page' => $max_page,
                'list' => $goods1,
                'type' => $type
            ));
        }


        unset($order1["discloseDate"]);
        unset($map1["status"]);
        $map1["status"] = "1";

        //最新

        $field = "a.id,a.title,a.description,a.content,a.image,a.originPrice,
        		a.price,a.qishu,a.fenshu,a.canyushu,a.limit_buy,a.maxqishu,a.status,
        		a.recommend";
        
        
        $order1["b.creatDate"] = "desc";


        if (IS_AJAX && $type == 3) {
            $goods2 = M("Goods")
            		->field($field)
            		->alias('a')
            		->join('left join ( SELECT goodsId, max(id) as id, COUNT(DISTINCT goodsId)
            				FROM (select id, goodsId from  yytb_periods_detail order by id desc )
            				a GROUP BY a.goodsId ORDER BY id DESC LIMIT 0,30) b on a.id = b.goodsId')
                    ->where('a.status=1')
                    ->order('a.id desc')
                    ->page($page, $show_index)
                    ->select();       
            
            

            foreach ($goods2 as &$v) {
                $v ['url'] = U('Weixin/Goods/product_detail2', 'id=' . $v ['id']);
            }

            json(array(
                'page' => $page,
                'max_page' => $max_page,
                'list' => $goods2,
                'type' => $type
            ));
        }

        //echo M('')->getLastSql();die;
        unset($order1["b.creatDate"]);

        //人气
        $order1["qishu"] = "desc";

        if (IS_AJAX && $type == 2) {
            $goods3 = M("Goods")
                    ->order($order1)
					->where('status = 1')
                    ->page($page, $show_index)
                    ->select();


            foreach ($goods3 as &$v) {
                $v ['url'] = U('Weixin/Goods/product_detail2', 'id=' . $v ['id']);
            }

            json(array(
                'page' => $page,
                'max_page' => $max_page,
                'list' => $goods3,
                'type' => $type
            ));
        }


        unset($order1["qishu"]);
        
        
        //价值
        
        if ($cgsta == 1) {
            $order1["originPrice"] = "asc";
            $span = ' <span class="fa fa-caret-up"></span>';
            $cgsta = 0;
        } else {
            $order1["originPrice"] = "desc";
            $span = ' <span class="fa fa-caret-down"></span>';
            $cgsta = 1;
        }

        
        if (IS_AJAX && $type == 4) {
			$where = '';
			$cgsta = I('get.cgsta');
			if ($cgsta == 1) {
				$where = '(maxqishu <> qishu and maxqishu <> 0 ) or canyushu > 0 and status = 1';
			}
			$cgsta = 0;
            $goods4 = M("Goods")
			        ->where($where)
                    ->order($order1)
                    ->page($page, $show_index)
                    ->select();


            foreach ($goods4 as &$v) {
                $v ['url'] = U('Weixin/Goods/product_detail2', 'id=' . $v ['id']);
            }

            json(array(
                'page' => $page,
                'max_page' => $max_page,
                'list' => $goods4,
                'type' => $type
            ));
        }

        unset($order1["originPrice"]);

        /*
         * 获取商品类型详情
         * */

        $goodstypes = M('goods_type')->where("status=1")
                ->order('taxis')
                ->select();
        $this->assign('goodstypes', $goodstypes);
        // dump($goods);exit;
        $this->assign('type', $type);
        $this->assign('cgsta', $cgsta);
        $this->assign('span', $span);
        $this->assign("title", C('site_title'));
        $this->display();
    }

}
