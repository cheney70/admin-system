<?php
namespace Cheney\Content\Services;

use App\Exceptions\FileNotExistException;
use Cheney\Content\Models\Categorys;
use Carbon\Carbon;

/**
 * Created by PhpStorm.
 * User: codeanti
 * Date: 2020-1-4
 * Time: 下午3:08
 */
class CategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Categorys::class);
    }
    /**
     * @return void
     */
    public function getCategorys($params){
        $model = Categorys::query();
        $model ->where('status',1)->where('parent_id',0);
        if (isset($params['type']) && !empty($params['type'])){
            $model ->where('type',$params['type']);
        }
        if (isset($params['Id']) && !empty($params['Id'])){
            $model ->where('id',$params['Id']);
        }

        $model -> with('children')
            ->orderBy('level','asc');

        $orderBy   = isset($params['orderBy']) ? $params['orderBy'] : 'id';
        $orderSort = isset($params['byAsc']) ? 'ASC' : 'DESC';
        $model->orderBy($orderBy,$orderSort);

        if(isset($params['groupBy']) && $params['groupBy']){
            $model->groupBy($params['groupBy']);
        }
        if(! $model->exists()){
            return false;
        }
			
        if(isset($params['page_num']) && $params['page_num']){
            $page    = isset($params['page']) ? $params['page'] : 1;
            $result = $model->paginate($params['page_num'],['*'],'page',$page);
        }else{
            $result = $model->get();
        }
        return $result;
    }

}
