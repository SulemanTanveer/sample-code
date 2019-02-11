<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use App\Classes\TitleUpdate;

class Category extends Model
{
	use TitleUpdate;

	protected $guarded =[];
    	
    protected static function boot(){

    	parent::boot();
    	
    	static::created(function($category){
            $category->update(['slug' => $category->name]);
        });
    
    }

    public function products()
    {
    	return $this->belongsToMany('App\Models\Product\Product');
    }
}
