<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
	use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description' ];
    protected $dates = ['deleted_at'];
    public function categories(){
    	return $this->belongsToMany('App\Models\Category','products_categories');
    }

    public function images(){
    	return $this->hasMany('App\Models\ProductImages');	
    }

}