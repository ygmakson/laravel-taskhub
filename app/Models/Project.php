<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable=['user_id', 'name'];

    public function users() {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }
}
