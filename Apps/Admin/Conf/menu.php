<?php
return array(
    'ADMIN_MENU' => array(
        // array( 'name' => '注销登录', 'mode' => 'Admin/login/logout', 'icon' => 'fa fa-lock', ),
        array(
            'name' => '商品管理',
            'mode' => 'Admin/Goods/index',
            'icon' => 'fa fa-shopping-cart'
        ),
        array(
            'name' => '晒单管理',
            'mode' => 'Admin/PeriodsShow/index',
            'icon' => 'fa fa-user'
        ),
        array(
            'name' => '用户管理',
            'mode' => 'Admin/Users/index',
            'icon' => 'fa fa-user'
        ),
        array(
            'name' => '商品类型',
            'mode' => 'Admin/GoodsType/index',
            'icon' => 'fa fa-stack-overflow'
        ),
        array(
            'name' => '抽奖管理',
            'mode' => 'Admin/Win/index',
            'icon' => 'fa fa-star-half-empty'
        ),
        
        array(
            'name' => '充值记录',
            'mode' => 'Admin/Recharge/index',
            'icon' => 'fa fa-video-camera'
        ),

    		array(
    				'name' => '幻灯管理',
    				'mode' => 'Admin/Banner/index',
    				'icon' => 'fa fa-photo'
    		),
		array(
			'name' => '广告管理',
			'mode' => 'Admin/Advert/index',
			'icon' => 'fa fa-skyatlas'
		),
        array(
            'name' => '个人资料',
            'mode' => 'Admin/Adminer/me',
            'icon' => 'fa fa-user'
        ),

        /*
        array(
            'name' => '用户管理',
            'mode' => '',
            'icon' => 'fa fa-user',
            'sub' => array(
                array(
                    'name' => '用户列表',
                    'mode' => 'Admin/user/index',
                    'icon' => 'fa fa-list-ul'
                ),
                array(
                    'name' => '新增用户',
                    'mode' => '',
                    'icon' => 'fa fa-user-plus',
                    'sub' => array(
                        array(
                            'name' => '用户列表',
                            'mode' => '',
                            'icon' => 'fa fa-list-ul'
                        ),
                        array(
                            'name' => '新增用户',
                            'mode' => '',
                            'icon' => 'fa fa-user-plus'
                        )
                    )
                    
                )
            )
            
        )
        */
    )
    
);