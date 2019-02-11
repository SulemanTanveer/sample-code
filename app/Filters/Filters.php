<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
	protected $request, $builder;

	protected $filters = [];

	function __construct(Request $request)
	{
		$this->request = $request;
	}
	/**
	 * [apply filters to the query]
	 * @param  [type] $builder [description]
	 * @return [type]          [description]
	 */
	public function apply($builder)
	{
		$this->builder = $builder;

		foreach ($this->getFilters() as $filter => $value) {
		
			if (method_exists($this, $filter)) {
		
				$this->$filter($this->request->$filter);
		
			}
		}
		
		return $this->builder;
	
	}
	public function getFilters()
	{		
		return $this->request->only($this->filters);
	}
}