<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Classes\LatestRecordScope;

class ProductType extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    
    protected $hidden = ['created_at','updated_at','deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LatestRecordScope);
    }
}
