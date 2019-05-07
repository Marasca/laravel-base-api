<?php

namespace DiegoMengarda\LaravelBaseApi\Repositories\Criteria;

use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\Block;
use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\Decide;
use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\FilterAnd;
use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\FilterDoesntHave;
use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\FilterHas;
use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\FilterOr;
use DiegoMengarda\LaravelBaseApi\Repositories\Criteria\Traits\FilterWith;
use DiegoMengarda\LaravelBaseApi\Repositories\Traits\OrderByTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Search
{
    use Block;
    use Decide;
    use FilterAnd;
    use FilterDoesntHave;
    use FilterHas;
    use FilterOr;
    use FilterWith;
    use OrderByTrait;

    /**
     * @var Builder
     */
    private $query;

    /**
     * @param Model $query
     * @return $this
     */
    public function setModel(Model &$query)
    {
        $this->query = $query->newQuery();
        return $this;
    }

    /**
     * @param Model $model
     * @param array $data
     * @return $this
     */
    public function run(Model &$model, array $data)
    {
        $this->setModel($model);
        $this->query = $this->loop($this->query, $data);
        // $this->query = $this->orderByFields($this->query, $data);

        return $this;
    }

    /**
     * @param $query
     * @param array $data
     * @return mixed
     */
    public function loop($query, array $data)
    {
        foreach ($data as $key => $item) {
            $this->decide($key, $item, $query);
        }
        return $query;
    }

    /**
     * @param $values
     * @return bool
     */
    public function isField($values)
    {
        return !(
            isset($values['block']) ||
            isset($values['has']) ||
            isset($values['orHas']) ||
            isset($values['doesntHave']) ||
            isset($values['orDoesntHave']) ||
            isset($values['with']) ||
            isset($values['and']) ||
            isset($values['or'])
        );
    }

    /**
     * get Model with queries.
     *
     * @return Builder
     */
    public function getQuery()
    {
        return $this->query;
    }
}
