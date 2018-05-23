<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Upload;

class GoodsTypeController extends AdminController
{

    public function index()
    {
        $this->assign('title', '商品类型管理');
        $types = M('goods_type')->where("status = 1")
            ->order(array(
            'taxis, id desc'
        ))
            ->select();
        $this->assign('types', $types);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            
            $data = M("goods_type")->create();
            if (! $data["typeName"]) {
                $this->error("类型名称不能为空");
            }
          /*   
            $upload = new Upload(); // 实例化上传类
            $upload->exts = array(
                'jpg',
                'gif',
                'png',
                'jpeg'
            ); // 设置附件上传类型
            $upload->rootPath = "upload/"; // 文件上传保存的根路径
            $upload->savePath = "icon/"; // 设置附件上传目录
            $upload->autoSub = false;
            
            $info = $upload->upload();
            
            if (! $info) {
                $this->error($upload->getError());
                exit();
            }
            
            $data["url"] = $upload->rootPath . $upload->savePath . $info["url"]["savename"]; */
            $flag = M("goods_type")->data($data)->add();
            
            if ($flag) {
                $this->success("添加成功", U("index"));
                exit;
            } else {
                $this->error("添加失败");
            }
        }
        
        $this->display();
    }
    
    // 返回商品类型列表 JSON
    public function lists()
    {
        $page = I('get.page', 1, 'int');
        
        $row = I('get.rows', 10, 'int');
        
        $skip = ($page - 1) * $row;
        $order = array(
            'taxis',
            'id' => 'desc'
        );
        
        // 筛选
        $where = array();
        
        $keyword = I('get.keyword', 0);
        if ($keyword)
            $where['title'] = array(
                'like',
                "%$keyword%"
            );
        
        $typeId = I('get.typeId', 0);
        if ($typeId)
            $where['typeId'] = $typeId;
        
        $status = I('get.status', 0);
        if ($status)
            $where['status'] = $status;
            
            // 获取商品
        $Goods_typeM = M('goods_type');
        $goods_type = $Goods_typeM->field('id, typename, url, description, status, taxis')
            ->where($where)
            ->order($order)
            ->limit($skip, $row)
            ->select();
        
        $count = $Goods_typeM->where($where)->count();
        
        $data = array(
            'count' => count($goods_type),
            'total' => $count,
            'page' => $page,
            'row' => $row,
            'goods_type' => $goods_type
        );
        
        admJson($data);
    }
    
    // 商品 详情页
    public function edit()
    {
        if (IS_POST)
            return $this->update();
        
        $id = I('get.id', 0);
        $this->assign('title', '商品类型');
        
        $goods_type = M('goods_type')->find($id);
        
        if (empty($goods_type))
            $this->error('商品类型不存在', 'javascript:self.close()');
        
        $this->assign('do', 'edit');
        $this->assign('goods_type', $goods_type);
       // layout("inc/tpl.min");
        $this->display();
    }

    /* 更新内容 */
    public function update()
    {
        $goods_type = D('Goods_type');
        $msg = false;
        $id = I('post.id', 0, 'int');
        
        $data = $goods_type->create();
        // dump($data);exit;
        
        if ($_POST["flag"] == "1") {
            $flag = $goods_type->where("id=$id")->save($data); // 根据条件更新记录
        }
        
        $flag = $goods_type->where("id=$id")->save($data); // 根据条件更新记录
        
        if ($flag) {
            $this->success("修改成功", U("index"));
            exit;
        } else {
            $this->error("修改失败");
        }
        //$this->display();
    }

    public function del()
    {
        $id = I('post.id', 0, 'int');
        
        $arr = array(
            'id' => $id,
            'a' => 'del'
        );
        
        if (! $id) {
            $arr['code'] = 501;
            $arr['msg'] = '参数 id 错误';
        } elseif (! M('goods_type')->delete($id)) {
            $arr['code'] = 500;
            $arr['msg'] = '删除 错误';
        }
        admJson($arr);
    }
    
    // 删除详情图片
    private function delImage()
    {
        $id = I('post.id', 0, 'int');
        
        $arr = array(
            'id' => $id,
            'a' => 'del'
        );
        
        if (! $id) {
            $arr['code'] = 501;
            $arr['msg'] = '参数 id 错误';
        } elseif (! M('goods_detail')->delete($id)) {
            $arr['code'] = 500;
            $arr['msg'] = '删除 错误';
        }
        return $arr;
    }
    
    // 切换状态
    public function toggleGoodsType()
    {
        $id = I('post.id', 0, 'int');
        
        if ($id)
            M()->execute("update __PREFIX__goods_type set status = if(status != 1, 1, 2) where id = $id");
        
        admJson(array(
            'admin' => 32
        ));
    }
}
