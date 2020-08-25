<?php
namespace App\Filters;
use App\Exceptions\FilterExeption;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function getPaginate() {
        $result = $this->request['paginate'];
        if (isset($result)) {
            return $result;
        } else {
            return 5;
        }
    }

    public function apply($builder) {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

     return $this->builder;

    }

    public function getFilters() {
        return array_filter($this->request->only($this->filters));
    }
}
