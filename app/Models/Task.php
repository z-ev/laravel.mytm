<?php

namespace App\Models;


use App\Filters\ProjectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Task extends Model
{
    use HasApiTokens;
    use Notifiable;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'body',
        'status',
        'deadline',
        'created_at',
        'updated_at',
    ];

    public function scopeFilter($query, ProjectFilter $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Обратная связь, каждая задача относится к проекту
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }

}
