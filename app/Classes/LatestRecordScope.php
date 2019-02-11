<?php

//global scope to return latest record
namespace App\Classes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LatestRecordScope implements Scope
{
    public $column;

    function __construct($column = 'created_at')
    {
        $this->column = $column;
    }
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->latest($this->getColumnName($model));
    }
    /**
     * [return column name]
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function getColumnName($model)
    {
        return $model->getTable().'.'.$this->column;
    }
}