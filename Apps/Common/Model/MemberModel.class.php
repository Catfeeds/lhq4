<?php
namespace Common\Model;
use Think\Model;

class MemberModel extends Model{

  /*  public function getTableName()
    {
        return self::$table_name;
    }*/

     public function getMembers()
     {

         //�������ַ�ʽ����Է���sample��DB
        // $db = self::__instance();
         $db=M('Member');
         //$db=self::__instance(SAMPLE_DB_ID);
/*
         $sql = "select " . self::$columns . " from " . self::getTableName() . " order by member_id desc";
         //var_dump($sql);
         $list = $db->query($sql)->fetchAll();*/
         $list = $this->order('member_id desc')->select();
         //var_dump($list);die;
         if ($list) {
             return $list;
         }

        return array();
     }

// //查询手机号
//     public function getPhone($phone)
//     {
//         if (!$phone || !is_numeric($phone)) {
//             return false;
//         }
//         $condition ["phone"] = $phone;
//         //$condition = array("AND" => $sub_condition);
//         $db = self::__instance();
//         $list = $db->find(self::getTableName(), self::$columns, $condition);
//         //var_dump($list);die;
//         if ($list) {
//             return $list[0];
//         }
//         return array();
//     }

     public function getMemberById($member_id)
     {
         if (!$member_id || !is_numeric($member_id)) {
            return false;
         }
         $sub_condition ["member_id"] = $member_id;
         //$condition = array("AND" => $sub_condition);
        // $db = self::__instance();
       //  $db=M('Member');
       // $list = $db->select(self::getTableName(), self::$columns, $condition);
         $list = $this->where($sub_condition)->select();
         //var_dump($list);die;
         if ($list) {
             return $list[0];
         }
         return array();
    }

// //获取pid
    public function getMemberByPid($member_id)
    {
        if (!$member_id || !is_numeric($member_id)) {
             return false;
         }

         $sub_condition ["pid"] = $member_id;
         //$condition = array("AND" => $sub_condition);
        //var_dump($sub_condition);//die;
       //  $db = self::__instance();
        // $list = $db->select(self::getTableName(), self::$columns, $condition);
        $list=$this->where($sub_condition)->select();
///         var_dump($list);die;
         if ($list) {
             return $list;
          }
          return array();
     }
//获取被邀请人的信息
    public function getMemberByMid($member_id)
    {
        if (!$member_id || !is_numeric($member_id)) {
            return false;
        }
        $a=date("Y-m-d",strtotime("-1 day"));
        $b=date("Y-m-d",strtotime("+1 day"));
        $sub_condition ["pid"] = $member_id;
       //n $sub_condition ["add_time"]=array(array('gt',$a),array('lt',$b));
        $list=$this->where($sub_condition)->field('member_id,pic,nickname,add_time,pid')->order('add_time desc')->select();

        if ($list) {
            return $list;
        }
        return array();
    }
//     //获取头像
//     public function getPic()
//     {

//         //
//         $db = self::__instance();
//         //$db=self::__instance(SAMPLE_DB_ID);
//         $sql = "select " . self::$columns . " from " . self::getTableName();
//         // var_dump($sql);die;
//         $list = $db->query($sql)->fetchAll();
//         //var_dump($list);//die;
//         $data = array();
//         foreach ($list as $key => $value) {
//             $data [$value['member_id']] = $value['pic'];
//         }
//         return $data;
//     }

//     //统计总人数
     public function getCounts()
     {
         //$db = self::__instance();

        // $condition = array();

         $num = count($this->select());
         //$condition1[DATE_FORMAT('add_time','%Y-%m-%d')]=DATE_FORMAT(now(),'%Y-%m-%d');
         /*$xz = $db->select ( self::getTableName(),"*");
         $c=array();
         $d=array();
         $time=24*3600*7;
         foreach($xz as $k=>$v){
             if(substr($v['add_time'],0,10)==date("Y-m-d",time())){
                  $c[]=$v;
             }
             if(substr($v['add_time'],0,10)<=date("Y-m-d",time()) && substr($v['add_time'],0,10)>=(date("Y-m-d",time()-$time))){                 $d[]=$v;
             }
         }*/
         //var_dump(count($d));die;
         return $num;
     }

//     //统计新增人数
     public function getnewCounts()
     {
        // $db = self::__instance();
         //$condition1[DATE_FORMAT('add_time','%Y-%m-%d')]=DATE_FORMAT(now(),'%Y-%m-%d');
         //$num = $db->select(self::getTableName(), "*");
         $num = $this->select();
         $c = array();
         $d = array();
         $time = 24 * 3600 * 7;
         foreach ($num as $k => $v) {
             if (substr($v['add_time'], 0, 10) == date("Y-m-d", time())) {
                $c[] = $v;
            }
             if (substr($v['add_time'], 0, 10) <= date("Y-m-d", time()) && substr($v['add_time'], 0, 10) >= (date("Y-m-d", time() - $time))) {
                 $d[] = $v;
             }
         }
         $list = count($c);
         //var_dump(count($c));die;
         return $list;
     }

     //统计日活跃人数
     public function getdayCounts()
     {
   /*      $db = self::__instance();
         $num = $db->select(self::getTableName(), "*");*/
         $num = $this->select();
         $weekcount = array();
         foreach ($num as $k => $v) {
             if (substr($v['login_time'], 0, 10) == date("Y-m-d", time())) {
                 $weekcount[] = $v;
             }
         }
         $list = count($weekcount);
         //var_dump(count($c));die;
         return $list;
     }

     //统计月活跃人数
     public function getmonthCounts()
     {
    /*     $db = self::__instance();
         $num = $db->select(self::getTableName(), "*");*/
         $num = $this->select();
         $monthcount = array();
         $time = 24 * 3600 * 30;
         foreach ($num as $k => $v) {
             if (substr($v['login_time'], 0, 10) <= date("Y-m-d", time()) && substr($v['login_time'], 0, 10) >= (date("Y-m-d", time() - $time))) {
                 $monthcount[] = $v;
             }
         }
         $list = count($monthcount);
         //var_dump(count($c));die;
         return $list;
     }

     //统计周活跃人数
     public function getweekCounts()
     {
      /*   $db = self::__instance();
         //$condition1[DATE_FORMAT('add_time','%Y-%m-%d')]=DATE_FORMAT(now(),'%Y-%m-%d');
         $num = $db->select(self::getTableName(), "*");*/
         $num = $this->select();
         $daycount = array();
         $time = 24 * 3600 * 7;
         foreach ($num as $k => $v) {
             if (substr($v['login_time'], 0, 10) <= date("Y-m-d", time()) && substr($v['login_time'], 0, 10) > (date("Y-m-d", time() - $time))) {
                 $daycount[] = $v;
             }
         }
         $list = count($daycount);
         //var_dump(count($c));die;
         return $list;
     }

     public function getMembersArray() {
         $list = $this->select();
         $data = array();
         $data['0'] = "不限";
         foreach ($list as $key => $value) {
             $data [$value['member_id']] = $value['nickname'];
         }
         return $data;
     }

     public function getMembersNicknameArray()
     {

         $list=$this->select();
         //var_dump($list);//die;
         $data = array();
         $data['0'] = "不限";
         foreach ($list as $key => $value) {
             $data [$value['member_id']] = $value['nickname'];
         }
         return $data;
     }
    //搜索会员名称
        public function SelectMemberByName($member_name) {
          //  $condition = array();
            if( $member_name!=""){
               // $condition['LIKE']=array("member_name"=>$member_name);
                $condition['nickname'] =array('like', "%$member_name%");//$member_name;
                // var_dump($condition);//die;
            }else{
                $condition['nickname'] ='';
            }
           // var_dump($condition);//die;
            $list =$this->where($condition)->field('nickname,member_id')->select();
            //var_dump($list);die;
            if ($list) {
                return $list;
            }
            return array ();
        }
        
    //获取当前用户的累计邀请收入
//     public function getTotaliviteArray()
//     {
//         $db = self::__instance();
//         //$db=self::__instance(SAMPLE_DB_ID);

//         $sql = "select " . self::$columns . " from " . self::getTableName();
//         // var_dump($sql);die;
//         $list = $db->query($sql)->fetchAll();
//         //var_dump($list);//die;
//         $data = array();

//         foreach ($list as $key => $value) {
//             $data [$value['member_id']] = $value['total_invite'];
//         }
//         return $data;
//     }

//     //获取总收徒数
//     public function getMemberCount($member_id)
//     {

//         $db = self::__instance();
//         $condition = array();
//         if ($member_id) {
//             $condition['pid'] = $member_id;
//         }
//         //echo $id;die;
//         $list = $db->select(self::getTableName(), "*", $condition);
//         //var_dump($list);die;
//         return $list;
//     }


    public function getCountByDate($phone, $user_num, $idfa, $start_date, $end_date)
     {
        // $db = self::__instance();
         //var_dump($db);
         // echo $end_date;//die;
       // $condition = array();
         if ($phone != '') {
             $sub_condition['phone'] = $phone;
         }
         if ($user_num != '') {
             $sub_condition['user_num'] = $user_num;
         }

//         //var_dump($user_num);die;
       /*  $sub_condition["add_time[<>]"] = array($start_date, $end_date);*/
        $sub_condition["add_time"] =array("egt",$start_date);
        $sub_condition["add_time"] =array("elt",$end_date);
      //  $condition["AND"] = $sub_condition;
         if ($idfa != '') {
             //$sub_condition['idfa']=$idfa;
            // $condition['LIKE'] = array("idfa" => $idfa);
             $sub_condition['identify'] = array('like', "%$idfa%");
         }
         $num = count($this->where($sub_condition)->select());
//         //var_dump($num);die;
        return $num;
     }

     public function count($phone, $user_num, $idfa)
     {
        // $db = self::__instance();

         $sub_condition = array();

         if ($phone != '') {
             $sub_condition['phone'] = $phone;
         }
         if ($user_num != '') {
             $sub_condition['user_num'] = $user_num;
         }
      /*   if (empty($sub_condition)) {
             $condition = array();
         } else {
            $condition["AND"] = $sub_condition;
         }*/
         //var_dump($user_num);die;
         if ($idfa != '') {
             //$sub_condition['idfa']=$idfa;
             $sub_condition['identify'] = array('like', "%$idfa%");
         }
         $num = count($this->where($sub_condition)->select());
         return $num;
     }

//     public function getMembersByPage($start, $page_size)
//     {
//         $db = self::__instance();

//         $condition = array();

//         $condition["ORDER"] = " member_id desc";
//         $condition['LIMIT'] = array($start, $page_size);

//         $list = $db->select(self::getTableName(), self::$columns, $condition);
//         //var_dump($list);
//         if ($list) {
//             return $list;
//         }
//         return array();
//     }

//     //
     public function countSearch($member_id, $phone, $user_num)
     {
      //   $db = self::__instance();
        // $condition = array();
         if ($member_id != "") {
            // $condition['LIKE'] = array("member_id" => $member_id);
             $condition['member_id'] = array('like', "%$member_id%");
         }
         if ($phone != "") {
             $condition['phone'] = array('like', "%$phone%");
         }
         if ($user_num != "") {
             $condition['user_num'] = array('like', "%$user_num%");
         }
         // var_dump($condition);die;
     //    $num = $db->count(self::getTableName(), $condition);
         $num=$this->where($condition)->count();
         //var_dump($num);die;
         return $num;
     }

//     //查询
     public function searchmember($member_id, $phone, $user_num, $start = '', $page_size = '')
     {/*
         $db = self::__instance();
         $limit = "";
         $where = "";
         if ($page_size) {
             $limit = " limit $start,$page_size ";
         }*/
         if ($member_id != "") {
             $condition['member_id'] = array('like', "%$member_id%");
         }

         if ($phone != "") {
             $condition['phone'] = array('like', "%$phone%");
         }
         if ($user_num != "") {
             $condition['user_num'] = array('like', "%$user_num%");
         }


        // $sql = "select * from " . self::getTableName() . " $where order by member.member_id desc $limit";
 //var_dump($sql);die;
        // $list = $db->query($sql)->fetchAll();
         $list=$this->where($condition)->limit($start,$page_size)->select();
         //var_dump($list);//die;
         if ($list) {
             return $list;
         }
         return array();
     }


     public function getAllMember($start = '', $page_size = '')
     {
       //  $db = self::__instance();
       /*  $limit = "";
         if ($page_size) {
             $limit = " limit $start,$page_size ";
         }*/
   /*      $sql = "select * from " . self::getTableName() . "  order by member.member_id desc $limit";

         $list = $db->query($sql)->fetchAll();*/
         $list=$this->order('member_id desc')->limit($start,$page_size)->select();
         //var_dump($list);
         if ($list) {
             return $list;
         }
         return array();
     }

     public function getMems($user_num, $phone, $idfa, $start, $page_size, $start_date = '', $end_date = '')
     {
        // $db = self::__instance();

        $condition = array();
         $sub_condition = array();
         if ($user_num != '') {
             $sub_condition['user_num'] = $user_num;
         }
         if ($phone != '') {
             $sub_condition['phone'] = $phone;
         }
         /*if ($start_date != '' && $end_date != '') {
             $sub_condition["add_time[<>]"] = array($start_date, $end_date);
         }*/
      if ($start_date == '' && $end_date != '') {

             $sub_condition['add_time'] = array('elt',$end_date);
         }
         if ($start_date != '' && $end_date == '') {
             $sub_condition['add_time'] = array('egt',$start_date);;
         }
         if ($start_date != '' && $end_date != '') {
             // $where["wd_time"] = array('between',$start_date,$end_date);
             $sub_condition["add_time"] = array(array('egt',$start_date),array('elt',$end_date));
         }
         //var_dump($sub_condition);//die;
       /*  if (empty($sub_condition)) {
             $condition = array();
         } else {
             $condition["AND"] = $sub_condition;
         }*/
         if ($idfa != '') {//$sub_condition['idfa']=$idfa;
             $condition['LIKE'] = array("identify" => $idfa);
         }
       //  var_dump($condition);

      //   $condition["ORDER"] = " member_id desc";
        $condition['LIMIT'] = array($start, $page_size);
        /* var_dump($condition);
         var_dump($sub_condition);*/

         //var_dump($condition);die;
         $list = $this->where($condition)->order("member_id desc")->select();

        //var_dump($list);die;
         if ($list) {
            return $list;
        }
         return array();
     }

// ///查询当天邀请人数
//     public function Todayinvite($member_id)
//     {
//         if (!$member_id || !is_numeric($member_id)) {
//             return false;
//         }

//         $sub_condition ["pid"] = intval($member_id);
//         $condition = array("AND" => $sub_condition);
//         //var_dump($condition);die;
//         $db = self::__instance();
//         $num = $db->select(self::getTableName(), self::$columns, $condition);
//         $c = array();

//         foreach ($num as $k => $v) {
//             if (substr($v['add_time'], 0, 10) == date("Y-m-d", time())) {
//                 $c[] = $v;
//             }
//             $list = count($c);
//             //var_dump($list);die;
//             return $list;
//         }
//     }

//     //当前用户当天邀请人数收益
//     public function Todaytotal($member_id)
//     {
//         if (!$member_id || !is_numeric($member_id)) {
//             return false;
//         }
//         //echo $member_id;die;
//         $sub_condition ["pid"] = $member_id;
//         $condition = array("AND" => $sub_condition);
//         // var_dump($time);die;
//         $db = self::__instance();
//         $num = $db->select(self::getTableName(), self::$columns, $condition);
//         // var_dump($num);die;
//         $c = 0;
//         //var_dump($num);die;
//         foreach ($num as $k => $v) {
//             if (substr($v['add_time'], 0, 10) == date("Y-m-d", time())) {
//                 $c += $v['invitee_sum'];
//             }
//         }
//         return $c;
//     }

// //在数据库里查询openid是否存在
//     public function getOpenid($openid)
//     {
//         if (!$openid || !is_numeric($openid)) {
//             return false;
//         }
//         $condition ["openid"] = $openid;
//         //$condition = array("AND" => $sub_condition);
//         $db = self::__instance();
//         $list = $db->select(self::getTableName(), self::$columns, $condition);
//         // var_dump($list);die;
//         if ($list) {
//             return $list[0];
//         }
//         return $list;
//     }

//     //在member添加一行数据
//     public function addMember($input_data)
//     {
//         if (!$input_data || !is_array($input_data)) {
//             return false;
//         }
//         $db = self::__instance();
//         //var_dump($db);
//         $id = $db->insert(self::getTableName(), $input_data);
//         //var_dump($id);die;
//         return $id;
//     }

 //修改member表中数据
     public function updateMember($member_id, $input_data)
     {
         if (!$input_data || !is_array($input_data)) {
             return false;
         }
         $condition['member_id'] = $member_id;
//dump($condition);die;
        // $id = $db->update(self::getTableName(), $input_data, $condition);
         $id= $this->where($condition)->save($input_data);
         return $id;
     }

    //通过idfa查询所对应的member_id;
    public function getMemId($idfa)
    {

        $sub_condition ["identify"] = $idfa;
        $list = $this->field('member_id')->where($sub_condition)->select();

        if ($list) {
            return $list[0]['member_id'];
        }
        return array();
    }

    //通过member_id获取idfa
    public function getIdfa($member_id){

        $sub_condition ["member_id"] = $member_id;
        $list = $this->field('idfa')->where($sub_condition)->select();
        if ($list) {
            return $list[0]['idfa'];
        }
        return array();
    }

//     //二维码
     public function getsAccessToken($appid, $secret)
     {
         //$db = self::__instance();

         //$ch = curl_init ();
         $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $secret;
         $qrcode = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": "1000"}}}';

         $res =$this->https_post($url, $qrcode);
         $jres = json_decode($res, true);
         $access_token = $jres['access_token'];
         return $access_token;
     }

     public function https_post($url, $data)
     {
         $ch = curl_init();

         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
         curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
         //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $tmpInfo = curl_exec($ch);
         if (curl_errno($ch)) {
             //curl_close( $ch )
             return $ch;
         } else {
             //curl_close( $ch )
             return $tmpInfo;
         }
         curl_close($ch);
     }

     public function downloadImageFromWeiXin($url)
     {
         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $package = curl_exec($ch);
         $httpinfo = curl_getinfo($ch);
         curl_close($ch);
         return array_merge(array('body' => $package), array('header' => $httpinfo));
     }


//     /* public function https_post($url){
//          $ch = curl_init();
//          curl_setopt($ch, CURLOPT_URL, $url);
//          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//          curl_setopt($ch, CURLOPT_HEADER, 0);
//          curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
//          curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
//          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//          $data = curl_exec($ch);
//          curl_close($ch);
//          return $data;
//      }*/

//     public function api_notice_increment($url, $data)
//     {
//         $ch = curl_init();
//         $header = "Accept-Charset: utf-8";
//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
//         curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//         curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
//         //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         $tmpInfo = curl_exec($ch);
//         /* if (curl_errno($ch)) {
//              //curl_close( $ch )
//              return $ch;
//          }else{

//         //curl_close( $ch )
//              return $tmpInfo;
//          }*/
//         return $tmpInfo;
//         curl_close($ch);
//     }

//     //

//     //openid获得member_id,nickname
//     public function getMemberidByOpenid($openid)
//     {
//         //通过idfa查询所对应的member_id;
//         $sub_condition ["openid"] = $openid;
//         $condition = array("AND" => $sub_condition);
//         $db = self::__instance();
//         $list = $db->select(self::getTableName(), "member_id", $condition);
//         if ($list) {
//             return $list[0];
//         }
//         return array();
//     }

//     public function getNicknameByOpenid($openid)
//     {
//         //通过idfa查询所对应的member_id;
//         $sub_condition ["openid"] = $openid;
//         $condition = array("AND" => $sub_condition);
//         $db = self::__instance();
//         $list = $db->select(self::getTableName(), "nickname", $condition);
//         if ($list) {
//             return $list[0];
//         }
//         return array();
//     }


     //查询余额和总收入,提现金额，完成任务总数
     public function SelectMoney($memberid)
     {
        $condition['member_id'] = $memberid;

        $list=$this->where($condition)->field('income,balance,total_task,finishs_task,total_wd,ip')->select();
       /*  dump($condition);
         dump($list);*/
        if ($list) {
             return $list[0];
        }
        return array();
     }

    //获取pid
    public function getMemberPid($member_id)
    {
        $sub_condition ["member_id"] = $member_id;
        $list = $this->field('pid,ip')->where($sub_condition)->select();
        //$list = $db->select(self::getTableName(), "pid,ip", $condition);
        if ($list) {
            return $list[0];
        }
        return array();
    }
    ///通过imember_id 查找他的被邀请人
    public function getMemberByMemberId($member_id)
    {
        $sub_condition ["pid"] = $member_id;
        $a = array();
        $list = $this->field('add_time')->where($sub_condition)->order('add_time desc')->select();
   /*     dump($list);
        foreach ($list as $k => $v) {
            $addtime[]=substr($v['add_time'], 0, 10);

        }
        $time[]= array_unique($addtime);
        dump($addtime);
        dump($time);die;*/
        //$list = $db->select(self::getTableName(), "pid,ip", $condition);
        if ($list) {
            return $list;
        }
        return array();
    }

    //通过时间进行分组
    ///通过imember_id 查找他的被邀请人
    public function getMemberBytime($start_time,$end_time,$member_id)
    {
        $sub_condition["add_time"] = array(array('egt',$start_time),array('elt',$end_time));
        $sub_condition["pid"] =$member_id;
           // dump($sub_condition);
        $list =$this->field('member_id,pid,nickname,add_time')->where($sub_condition)->select();
         $count=count($list);
//dump($list);
      //  die;
        foreach ($list as $k => $value) {
          //dump($value);
            $list[$k]['count'] = $count;
        }
       // dump($list);
        if ($list) {
            return $list;
        }

        return array();
    }
    //   ///通过imember_id 查找他的被邀请人
    public function getCountMemberBytime($start_time,$end_time)
    {
        /*  $sub_condition ["add_time"] = $time;
/*   $sub_condition["add_time"] =array("egt",$start_date);
     $sub_condition["add_time"] =array("elt",$end_date);*/
        $sub_condition["add_time"] = array(array('egt',$start_time),array('elt',$end_time));
        //dump($sub_condition);
        $list =count($this->field('member_id,nickname,add_time')->where($sub_condition)->select());
        // dump($list);die;
        if ($list) {
            return $list;
        }
        return array();
    }
        //通过pid查询所对应邀请人的信息

    public function getinvitePeople($pid)
    {

        $sub_condition ["member_id"] = $pid;
        $list = $this->where($sub_condition)->select();
        //$list = $db->select(self::getTableName(), self::$columns, $condition);
        if ($list) {
            return $list[0];
        }
        return array();
    }

//     /**
//      *  每次提现后  对应的总金额变为提现后的数量
//      * @param $msid   任务的id
//      * @return int
//      **/
     public function updateBalance($id, $input_date)
     {
       /*  if (!$input_date || !is_array($input_date)) {
             return false;
         }
         $db = self::__instance();*/
         $condition ["member_id"] = $id;
        // $condition = array("member_id[=]" => $mid);
         //$id = $db->update(self::getTableName(), $input_date, $condition);
         $id=$this->where($condition)->save($input_date);
         return $id;
     }

//     //消息对应的人
     public function getMembernameArray()
     {
       //  $db = self::__instance();
        // $sql = "select " . self::$columns . " from " . self::getTableName();
        // $list = $db->query($sql)->fetchAll();
         $list=$this->select();
         $data = array();
         $data['0'] = "所有人";
         foreach ($list as $key => $value) {
             $data [$value['member_id']] = $value['member_name'];
         }
         return $data;
     }

//     //查询id所对应的name
//     public function getAppByName($app_name)
//     {
//         if (!$app_name) {
//             return false;
//         }

//         $sub_condition ["app_name"] = $app_name;

//         $condition = array("AND" => $sub_condition);
//         //var_dump($condition);
//         $db = self::__instance();
//         $list = $db->select(self::getTableName(), self::$columns, $condition);
//         if ($list) {
//             return $list;
//         }
//         return array();
//     }


     //搜索会员应用
    public function SelectMemberName($nickname) {
         if ($nickname != "") {
             $condition['nickname'] = array('like', "%$nickname%");
         }
         $list=$this->where($condition)->field("member_id")->select();
         if ($list) {
             return $list;
         }
         return array();
     }

//     //查询用户id
     public function selectId($msg_to)
     {
         if (!$msg_to || !is_numeric($msg_to)) {
             return false;
         }
         $condition ["member_id"] = $msg_to;
         //$condition = array("AND" => $sub_condition);
       //  $db = self::__instance();
       //  $list = $db->select(self::getTableName(), 'member_id', $condition);
         $list = $this->where($condition)->select();
         // var_dump($list);die;
         if ($list) {
             return $list[0];
         }
         return array();
     }
//}
    //分页
    public function search($phone,$idfa,$member_id,$start_date,$end_date,$search){
        $perPage = 25;
       // if ($search) {
           /* if ($user_num != "") {
                $where['user_num'] = array('like', "%$user_num%");
            }*/
            if ($idfa != "") {
                $where['identify'] = $idfa;
            }
            if ($phone != "") {
                $where['phone'] = $phone;
            }
        if ($member_id != "") {
            $where['member_id'] = $member_id;
        }

            if ($start_date == '' && $end_date != '') {
                $where['add_time'] = array('elt',$end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $where['add_time'] = array('egt',$start_date);;
            }
            if ($start_date != '' && $end_date != '') {
                // $where["wd_time"] = array('between',$start_date,$end_date);
                $where["add_time"] = array(array('egt',$start_date),array('elt',$end_date));
            }

            // $join = "LEFT JOIN provider ON app.provider_id =provider.provider_id";
     //   }
     //  dump($where);//die;
        $count = count($this->where($where)->select());
       // dump($count);
        $pageObj = new \Think\Page($count,$perPage);

        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj -> setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
        //$pageObj -> setConfig('theme','%FIRST% %LINK_PAGE% %DOWN_PAGE% %UP_PAGE% %HEADER% ');
       // $pageObj->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% <span>当前是第%NOW_PAGE%页 总共%TOTAL_PAGE%页</span> ');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this
            // ->join($join)
            ->order("member_id desc")                    // 排序
            ->where($where)            // 翻页
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
      //  dump($data);
//dump($data);
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

    // 会员关系分页
    public function search_rel($member_id,$user_num,$phone,$search){
        $perPage = 25;
       // if ($search) {
            if ($member_id > 0) {
                $where['member_id'] =$member_id;
            }
            if ($user_num != "") {
                $where['user_num'] = array('like', "%$user_num%");
            }
            if ($phone != "") {
                $where['phone'] = $phone;
            }
           // $join = "LEFT JOIN provider ON app.provider_id =provider.provider_id";
       // }

        $count = count($this->where($where)->select());
        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
       // $pageObj -> setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this
           // ->join($join)
            ->order("member_id desc")                    // 排序
            ->where($where)            // 翻页
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
//dump($data);
       // dump($where);dump($count);dump($data);die;
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

    public function search_financeList($user_num,$nickname){
        $perPage = 25;
        if ($user_num != "") {
            $where['user_num'] = $user_num;
        }
        if ($nickname != "") {
            $where['nickname'] = array('like', "%$nickname%");
        }
      //  dump($where);
        $count = count($this->where($where)->select());
       // dump($count);die;
        $pageObj = new \Think\Page($count,$perPage);

        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj -> setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this->order("member_id desc")
            ->where($where) // 排序
        ->limit($pageObj->firstRow.','.$pageObj->listRows)            // 翻页
        ->select();

        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

    public function search_memberDetails($id){
        $perPage = 25;
        if ($id != "") {
            $where['pid'] = $id;
        }
        $count = count($this->where($where)->select());
        //dump($count);
        $pageObj = new \Think\Page($count,$perPage);

        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj -> setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this->order("member_id desc")
            ->where($where)// 排序
        ->limit($pageObj->firstRow.','.$pageObj->listRows)            // 翻页
        ->select();

        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

    /**
     * 通过手机号获取验证码
     * @param $phone
     * @return mixed
     */
    public function getPhoneInfo($phone)
    {
        if (!empty($phone)) {
            $data = array(
                'phone' => $phone,
            );
            $info = D('Member')->field('phone,yzm_code,identify,idfa,member_id')->where($data)->find();
            if ($info) {
                return $info;
            }
        }
    }

    /**
     * 更新验证码
     * @param $code
     * @param $phone
     * @return bool
     */
    public function addCode($code,$phone)
    {
        if(!empty($code)) {
            $data = array(
                'phone'=>$phone,
            );
            $data1['yzm_code'] = $code;
            $re = D('Member')->where($data)->save($data1);
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 获取用户信息
     * @param $phone
     * @return mixed
     */
    public function checkPhone($phone)
    {
        if (!empty($phone)) {
            $data['phone'] = $phone;
            $re = D('member')->where($data)->find();
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 添加idfa
     * @param $identify
     * @param $phone
     * @return bool
     */
    public function addMemberIdfa($identify,$phone)
    {
        if (!empty($identify) && !empty($phone)) {
            $where['phone'] = $phone;
            $data['identify'] = $identify;
            $re =$this->where($where)->save($data);
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 更改idfa字段
     * @param $phone
     * @param $identify
     * @return bool
     */
    public function saveMemberIdfa($idfa,$phone)
    {
        if (!empty($idfa) && !empty($phone)) {
            $where['phone'] = $phone;
            $data['idfa'] = $idfa;
            $re = $this->where($where)->save($data);
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 根据identify查找
     * @param $idfentify
     * @return mixed
     */
    public function checkIdentify($idfentify)
    {
        if (!empty($idfentify) ) {

            $where['identify'] = $idfentify;
            $re = $this->where($where)->find();
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 更新手机号
     * @param $phone
     * @param $identify
     * @return bool
     */
    public function updataPhone($phone,$identify,$code)
    {
        if (!empty($identify) && !empty($phone)) {
            $where['identify'] = $identify;
            $data['phone'] = $phone;
            $data['yzm_code'] = $code;
            $re = $this->where($where)->save($data);
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 更新验证码
     * @param $code
     * @param $phone
     * @return bool
     */
    public function upDataCode($code,$phone)
    {
        if (!empty($code) && !empty($phone)) {
            $data['yzm_code'] = $code;
            $where['phone'] = $phone;
            $re = $this->where($where)->save($data);
            if ($re) {
                return $re;
            }
        }
    }

    /**
     * 获取openid
     * @param $member_id
     * @return mixed
     */
    public function getOpenid($member_id)
    {
        if (!empty($member_id)) {
            $where['member_id'] = $member_id;
            $re = $this->field('openid')->where($where)->find();
            if ($re) {
                return $re;
            }
        }
    }

}