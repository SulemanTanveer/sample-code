<?php
/**
 * product search filters 
 * when ever you need you filter just add new method but
 * name will be same as param name and set the method in $filters array  below
 * 
 */

namespace App\Filters;

use Illuminate\Http\Request;
use App\Models\Product\Product;

class ProductsFilters extends Filters
{
	protected $filters = ['search'];

	protected function search($product)
	{  
		return	$this->builder->where(function ($query) use ($product) {
            		$query->where('name', 'like', '%'.str_replace(' ', '', $product).'%')
            			->orWhere('description', 'like', '%'.str_replace(' ', '', $product).'%');
            	});
	}
}