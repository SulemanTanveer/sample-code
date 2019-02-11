<?php

namespace App\Classes;

/**
 * 
 */
trait TitleUpdate
{
	/**
	 * [make the unique slug for every products and categories]
	 * @param [type] $value [description]
	 */
	public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-".$this->id;
        }
        
        $this->attributes['slug'] = $slug;
    }
}