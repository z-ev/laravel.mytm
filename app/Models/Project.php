<?php

namespace App\Models;

use App\Filters\ProjectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Project extends Model
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


    /**
     * @param $query
     * @param ProjectFilter $filters
     * @return mixed
     */
    public function scopeFilter($query, ProjectFilter $filters)
    {

        return $filters->apply($query);

    }


    /**
     * Проект имеет множество задач
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks() {

        return $this->hasMany('App\Models\Task');

    }

    /**
     * У кжадого проекта есть свой пользователь
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projects()
    {

        return $this->belongsTo('App\Models\User', 'project_id');

    }


}
