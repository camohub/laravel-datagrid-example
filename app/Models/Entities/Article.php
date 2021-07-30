<?php

namespace App\Models\Entities;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Article extends Model
{
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [];


	//protected $with = ['user'];


	public function user()
	{
		return $this->belongsTo(User::class)->withTrashed();
	}


	public function categories()
	{
		return $this->belongsToMany(Category::class, 'articles_categories');
	}

	public function scopeVisible($query)
	{
		return $query->where('visible', TRUE);
	}
}
