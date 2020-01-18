<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
	use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image' ];
    protected $dates = ['deleted_at'];

    public function products(){
    	return $this->belongsToMany('App\Models\Product','products_categories');
    }


}