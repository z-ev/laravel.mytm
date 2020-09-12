<?php

namespace App\Filters;

use Illuminate\Http\Request;

/**
 * Class Filters
 * @package App\Filters
 */
abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    /**
     * Filters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return int|mixed
     */
    public function getPaginate()
    {
        $result = $this->request['paginate'];

        if (isset($result)) {
            return $result;
        } else {
            return 5;
        }
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }
}
