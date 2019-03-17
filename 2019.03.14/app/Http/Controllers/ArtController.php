<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use DB;
use Validator;

class ArtController extends CheckController
{
    public function index(Request $request){
        # 获取当前页数
    	$page = $request->get('page') ?? 1;
        # 获取模糊搜索参数
        $like['datemin'] = $request->get('datemin') ?? '2019-03-01';
        $like['datemax'] = $request->get('datemax') ?? date('Y-m-d');
        $like['search']  = $request->get('search')  ? trim($request->get('search')) : '';
        if( $like['search'] ){
            # 获取文章总数据
            $count = Article::where('title','like','%'.$like['search'].'%')->whereBetween('dtime', [$like['datemin'], $like['datemax']])->count();
        }else{
            
            # 获取文章总数据
            $count = Article::whereBetween('dtime', [$like['datemin'], $like['datemax']])->count();
        }
        # 调用模糊搜索方法
        $data = $this -> likeSearch($like,$page);        
        # 渲染文章列表页视图
    	return view('article.index',compact('data','count','like') );
    }

    private function likeSearch($like,$page){
        # 生成模糊搜索
        if( $like['search'] ){
            # 获取文章总数据
            $count = Article::where('title','like','%'.$like['search'].'%')->whereBetween('dtime', [$like['datemin'], $like['datemax']])->count();
            # 获取查询数据
            $data = Article::whereBetween('dtime', [$like['datemin'], $like['datemax']])
                -> where('title','like','%'.$like['search'].'%')
                -> offset(($page-1)*10)
                -> limit(10)
                -> orderBy('id','desc')
                -> get();
        }else{                

            # 获取查询数据
            $data = Article::whereBetween('dtime', [$like['datemin'], $like['datemax']])
                -> offset(($page-1)*10)
                -> limit(10)
                -> orderBy('id','desc')
                -> get();
        }

        return $data;
    }

    public function add(){
        # 渲染文章添加页视图
    	return view('article.add');
    }

    public function insert(Request $request){

    	$data = $request->all();

    	$validator = Validator::make($data, [
            'title' => 'required|min:2',
            'desn' => 'required'
        ], [
            'title.required' => '标题不能为空',
            'title.min' => '标题最少2位',
            'desn.required' => '描述不能为空'
        ]);

       $data['dtime'] = date('Y-m-d');
        // 验证是否通过
        if ($validator->fails()) {
            return response()->json(['code' => 300, 'msg'  => $validator->errors()->all()[0] ]);
        }
        # 执行入库
        $data['id'] = Article::insertGetId($data);
        $data['show_url'] = route('art.show',$data['id']);
        $data['edit_url'] = route('art.edit',$data['id']);
        $data['del_url'] = route('art.del',$data['id']);

        return response()->json(['code' => 201, 'msg' => '添加成功！','data'=>$data]);
        
    }

    public function show(int $id){
    	$data = Article::where('id',$id)->first();
    	return view('article.show',compact('data'));
    }

    public function edit(int $id){
    	$data = Article::where('id',$id)->first();
    	return view('article.update',compact('data'));
    }

    public function update(Request $request,int $id){

    	$data = $request->all();

    	$validator = Validator::make($data, [
            'title' => 'required|min:2',
            'desn' => 'required'
        ], [
            'title.required' => '标题不能为空',
            'title.min' => '标题最少2位',
            'desn.required' => '描述不能为空'
        ]);

       $data['dtime'] = date('Y-m-d');
        // 验证是否通过
        if ($validator->fails()) {
            return response()->json(['code' => 300, 'msg'  => $validator->errors()->all()[0] ]);
        }
        # 执行入库
        Article::where('id', $id)->update($data);
        $data['id'] = $id;
        $data['show_url'] = route('art.show',$data['id']);
        $data['edit_url'] = route('art.edit',$data['id']);
        $data['del_url'] = route('art.del',$data['id']);

        return response()->json(['code' => 202, 'msg' => '修改成功！','data'=>$data]);
    }

    public function del(Request $request,int $id){
        # 执行删除
    	Article::where('id',$id)->delete();
        # 获取当前页
        $page = $request->get('page') ?? 1;
        # 查询本页最后一条数据
        $data = Article::
                   offset(($page-1)*10)
                -> limit(10)
                -> orderBy('id','desc')
                -> get() -> toArray();
        $data['id'] = $id;
        $data['show_url'] = route('art.show',$data['id']);
        $data['edit_url'] = route('art.edit',$data['id']);
        $data['del_url'] = route('art.del',$data['id']);
    	return response()->json(['code' => 204, 'msg' => '删除成功！', 'data' => $data[9]]);
    }
}
