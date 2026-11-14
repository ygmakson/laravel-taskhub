<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable=['project_id', 'assignee_id', 'title', 'description', 'status', 'due_date'];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function assignee() {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
