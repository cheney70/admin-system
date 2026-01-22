<?php
namespace Cheney\Content\Services;

use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: codeanti
 * Date: 2020-1-4
 * Time: 下午3:08
 */
class BaseService
{
    const CACHE_USER_KEY = 'user_login_token_key';
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * 创建模块
     * @param String $modular_name
     * @param String $modular_code
     * @param Int $parent_id
     */
    public function create($data)
    {
        return $this->model::create($data);
    }

    /**
     * 批量插入
     * @param $data
     * @return mixed
     */
    public function installAll($data)
    {
        $table = (new $this->model())->getTable();
        return \DB::table($table)->insert($data);
    }

    /**
     * 删除
     * @param $param 条件
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function delete($param)
    {
        $find = $this->getParam($param);
        $first = $find->first();
        return $first ? $first -> delete() : 0;
    }

    /**
     * 更新方法
     * @param $id
     * @param $setData
     * @return mixed
     */
    public function update(Int $id , Array $setData)
    {
        $model = $this->getById($id);
        return $this->updateData($model,$setData);
    }

    /**
     * @param array $params
     * @param array $setData
     * @return mixed
     */
    public function updateByWhere(Array $params , Array $setData)
    {
        $model = $this->getByWhere($params);
        return $this->updateData($model,$setData);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isExists(Array $params)
    {
        return $this->getParam($params)->exists();
    }

    /**
     * 查询总条数
     * @param array $params
     * @return int
     */
    public function total(Array $params)
    {
        return $this->getParam($params)->count();
    }

    /**
     * 设置修改数据
     * @param $model
     * @param $setData
     * @return mixed
     */
    protected function updateData($model,$setData){
        if(isset($setData['updated_by']) && !empty($setData['updated_by'])){
            $model->updated_by = $setData['updated_by'];
        }
        $model->updated_at   = Carbon::now();
        $model->save();
        return $model;
    }

    /**
     * 获取列表
     * @param $params orderBy 排序字段
     * @param $params byAsc 是否正序排列，默认最新的排在最前面
     * @param $params groupBy 分组条件
     * @return bool|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll(Array $params,$col='*',$with=null) {
        $model = $this->getParam($params,$with);
        $orderBy   = isset($params['orderBy']) ? $params['orderBy'] : 'id';
        $orderSort = isset($params['byAsc']) ? 'ASC' : 'DESC';

        $model->orderBy($orderBy,$orderSort);
        if(isset($params['groupBy']) && $params['groupBy']){
            $model->groupBy($params['groupBy']);
        }
        if(! $model->exists()){
            return false;
        }
        $model->selectRaw($col);
        if(isset($params['page_num']) && $params['page_num']){
            $page    = isset($params['page']) ? $params['page'] : 1;
            $result = $model->paginate($params['page_num'],['*'],'page',$page);
        }else{
            $result = $model->get();
        }
        return $result;
    }

    /**
     * 获取详情
     * @param $params
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getByWhere(Array $params,$with=null,$col='*') {
        $model = $this->getParam($params,$with);
        if(! $model->exists()){
            return false;
        }
        $model->selectRaw($col);
        $result = $model->first();
        return $result;
    }

    /**
     * @param Int $id
     * @return mixed
     */
    public function getById($id,$with=null,$col='*') {
        $model = $this->model;
        if(!is_null($with)){
            $result = $model::with($with)->selectRaw($col)->findOrFail($id);
        }else{
            $result = $model::selectRaw($col)->findOrFail($id);
        }
        return $result;
    }

    /**
     * 构造查询语句
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getParam(Array $params,$with=null) {
        $model = $this->model::query();
        if(!is_null($with)){
            $model->with($with);
        }
        if(isset($params['id']) && !empty($params['id'])) {
            $model -> where('id', $params['id']);
        }
        if(isset($params['created_at']) && $params['created_at'] !== '') {
            $model -> where('created_at', $params['created_at']);
        }
        return $model;
    }
}
