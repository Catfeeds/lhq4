<?php
/*require ('../include/init.inc.php');
require ('../mobile/checkDevice.php');

checkDevice();
$member_id =$_SESSION['member_id'];

Template::assign ( 'member_id', $member_id );
Template::display ( 'mobile/about.tpl' );*/

namespace Mobile\Controller;
use Think\Controller;
class YykqController extends CommonController {
    public function yykq(){

        $this->display( );
    }


}