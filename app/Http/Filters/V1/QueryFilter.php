<?php

namespace App\Http\Filters\V1;

use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $builder;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // sort
    public function sort($value)
    {
        $sortFields = explode(',', $value);
        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if(strpos($sortField, '-') == 0)
            {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }

            if(!in_array($sortField, $this->sortable) && !array_key_exists($sortField, $this->sortable))
            {
                continue;
            }

            $columnName = $this->sortable[$sortField] ?? null;

            if($columnName == null)
            {
                $columnName = $sortField;
            }
            $this->builder->orderBy($columnName, $direction);
        }
    }
    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->request->all() as $key => $value) {
            // key is query string parameters
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
        return $this->builder;
    }

}