<?php

namespace Cheney\AdminSystem\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'name',
        'parent_id',
        'path',
        'component',
        'icon',
        'type',
        'sort',
        'status',
        'is_hidden',
        'keep_alive',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'type' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
        'is_hidden' => 'boolean',
        'keep_alive' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotHidden($query)
    {
        return $query->where('is_hidden', 0);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRoot($query)
    {
        return $query->where('parent_id', 0);
    }
}