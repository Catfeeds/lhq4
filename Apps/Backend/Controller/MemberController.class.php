<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\Pagination;
class MemberController extends ComController {
    public function member(){

        $method =$member_id= $user_num=$phone =$search= $idfa= $page_no = $start_date = $end_date = $start_time = $end_time = '';
        extract ( $_GET, EXTR_IF_EXISTS );
        //var_dump($_GET);
         //  $memberid = D('Member')->getMemberById($member_id);
          // var_dump($memberid);die;
        $data =D('Member')-> search($phone,$idfa,$member_id,$start_date,$end_date,$search);
       // $this->assign ('members',$members);
        $this->assign('members', $data['data']);
        $this->assign('page', $data['page']);


        $this->display();
    }
    //个人信息
    public function myajax(){
        $member_id=$_POST['id'];
        $sj=D('Member')->getMemberById($member_id);
//var_dump($sj);die;
        echo json_encode($sj);die;
    }

    //收益详情
    public function mem_incomeDetails(){
        $method = $member_id =  $page_no = '';
        extract ( $_GET, EXTR_IF_EXISTS );

        $incomes =  D('IncomeDetails')->getDetailById($member_id);

        $members = D('Member')->getMembersArray();
        $missions =D('Mission')->getMissionsArray();
       // $this->assign ('incomes',$incomes);
//Template::assign ('detail',$detail);
        //START 数据库查询及分页数据
        $row_count =count($incomes);
        $page_size = PAGE_SIZE;
        $page_no=$page_no<1?1:$page_no;
        $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
        $total_page=$total_page<1?1:$total_page;
        $page_no=$page_no>($total_page)?($total_page):$page_no;
        $start = ($page_no - 1) * $page_size;

//END
// 显示分页栏

        $page=Pagination::showPager("taskDetails?member_id=$member_id",$page_no,PAGE_SIZE,$row_count);

//$tasks = Task::getTasksByPage($start, $page_size);

        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page', $page);
        $this->assign ( '_GET', $_GET );
        $this->assign ('missions',$missions);
        $this->assign ('members',$members);


        $this->incomes= $incomes;

        $this->display();
    }
    //任务详情
    public function taskDetails(){
        $method = $member_id = '';
        extract ( $_GET, EXTR_IF_EXISTS );
//var_dump($_GET);//die;

      //  $details = D('IncomeDetails')->getDetailById($member_id);
        $apps = D('App')->getAppsArray();
        $missions =D('Mission')->getMissionsArray();
        $data =D('TaskDetails')->search($member_id);
        //dump($data);die;
        $this->assign('details', $data['data']);
        $this->assign('page', $data['page']);
        $this->assign ('missions',$missions);
        $this->assign ('apps',$apps);

        $this->display();
    }
    //会员关系
    public function memberRel(){
        $method =$member_id= $user_num= $phone = $search='';
        extract ( $_GET, EXTR_IF_EXISTS );
      //  $members = D('Member')->getMembers();
        $data =D('Member')->search_rel($member_id,$user_num,$phone,$search);
        $this->assign('members', $data['data']);
        $this->assign('page', $data['page']);
       // $this->members= $members;
        $this->display();
    }
    //查看详情
    public function memberDetails(){
        $id=I('get.member_id');

        $data = D('Member')->search_memberDetails($id);
      //  dump($data);die;
        $this->assign('membe', $data['data']);
        $this->assign('page', $data['page']);
        $this->display();
    }
    //会员统计
    public function stat(){
        $id=I('get.member_id');
        //dump($id);
        $counts =D('Member')->getCounts();
        $newCounts = D('Member')->getnewCounts();
        $dayCounts =D('Member')->getdayCounts();
        $weekCounts = D('Member')->getweekCounts();
        $monthCounts = D('Member')->getmonthCounts();
        $this->assign ('newCounts',$newCounts);
        $this->assign  ('counts',$counts);
        $this->assign  ('dayCounts',$dayCounts);
        $this->assign ('weekCounts',$weekCounts);
        $this->assign  ('monthCounts',$monthCounts);
        $this->display();
    }

}
?>