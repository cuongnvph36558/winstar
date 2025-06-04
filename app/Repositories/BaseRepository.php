<?php
namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Base;
/**
 * Class DistrictService
 * @package App\Services
 */
class BaseRepository extends BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(
        Model $model
    ){
        $this->model = $model;
    }

    public function pagination(
        array $column = ['*'], 
        array $condition = [], 
        int $perPage = 1,
        array $extend = [],
        array $orderBy = ['id', 'DESC'],
        array $join = [],
        array $relations = [],
        array $rawQuery = [],
        
    ){
        $query = $this->model->select($column);
        return $query  
                ->keyword($condition['keyword'] ?? null)
                ->publish($condition['publish'] ?? null)
                ->relationCount($relations ?? null)
                ->CustomWhere($condition['where'] ?? null)
                ->customWhereRaw($rawQuery['whereRaw'] ?? null)
                ->customJoin($join ?? null)
                ->customGroupBy($extend['groupBy'] ?? null)
                ->customOrderBy($orderBy ?? null)
                ->paginate($perPage)
                ->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

    public function create(array $payload = []){
        $model = $this->model->create($payload);
        return $model->fresh();
    }

    public function delete(int $id = 0){
        return $this->findById($id)->delete();
    }

    public function forceDelete(int $id = 0){
        return $this->findById($id)->forceDelete();
    }


    public function update(int $id = 0, array $payload = []){
        $model = $this->findById($id);
        $model->fill($payload);
        $model->save();
        return $model;
    }

    public function updateByWhereIn(string $whereInField = '', array $whereIn = [], array $payload = []){
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
    }

    public function all(){
        return $this->model->all();
    }
   public function findByid(
    int $modelId,
    array $column = ['*'],
    array $relation = []
    ){
        return $this->model->select($column)->with($relation)->findOrFail($modelId)
   }
}
