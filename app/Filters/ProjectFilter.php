<?php


namespace App\Filters;


class ProjectFilter extends Filters
{
    protected $filters = [
        'id',
        'user_id',
        'title',
        'body',
        'status',
        'deadline',
        'created_at',
        'updated_at',
        'order_by',
        'order_dir',
        'tasks',
        'projects',

    ];

    protected function tasks ($tasks) {
        return $this->builder->with('tasks');
    }

    protected function projects ($projects) {
        return $this->builder->with('projects');
    }

    protected function id($id) {
        return $this->builder->where('id', $id);
    }

    protected function user_id($user_id) {
        return $this->builder->where('user_id', $user_id);
    }

    protected function status($status) {
        return $this->builder->where('status', $status);
    }

    protected function body($body) {
        return $this->builder->where('body',  'like', "%$body%");
    }

    protected function title($title) {
        return $this->builder->where('title',  'like', "%$title%");
    }

    protected function deadline($deadline) {
        return $this->builder->where('deadline',  'like', "%$deadline%");
    }
    protected function created_at($created_at) {
        return $this->builder->where('created_at',  'like', "%$created_at%");
    }

    protected function updated_at($updated_at) {
        return $this->builder->where('updated_at',  'like', "%$updated_at%");
    }

    protected function order_by($order_by) {
        isset($this->request['order_dir']) ? $order_dir = $this->request['order_dir'] : $order_dir = 'desc';
        return $this->builder->orderBy($order_by, $order_dir);
    }










}
