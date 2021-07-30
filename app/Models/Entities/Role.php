<?php

namespace App\Models\Entities;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Role extends Model
{

	use SoftDeletes;

	const ROLE_ADMIN = 'admin';
	const ROLE_REDACTOR = 'redactor';
	const ROLE_REGISTERED = 'registered';

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


	public function users()
	{
		return $this->belongsToMany(User::class, 'users_roles');
	}
}
