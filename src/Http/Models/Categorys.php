<?php

namespace Cheney\Content\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Cheney\Contnet\Datatase\Models\Articles;

class Categorys extends Model
{
    protected $table= 'categorys';
    use SoftDeletes;

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(){
        return $this->hasMany(Articles::class,'type_id');
    }

    /**
     * 查找自己的子级
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(get_class($this), 'parent_id' ,'id');
    }

    /**
     * 无限分级
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allChildren() {
        return $this->children()->with( 'children' )->where('status',1)->orderBy('level','asc');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
}
