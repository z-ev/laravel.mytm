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

    /**
     * @param $tasks
     * @return mixed
     */
    protected function tasks ($tasks)
    {

        return $this->builder->with('tasks');

    }

    /**
     * @param $projects
     * @return mixed
     */
    protected function projects ($projects)
    {

        return $this->builder->with('projects');

    }

    /**
     * @param $id
     * @return mixed
     */
    protected function id($id)
    {

        return $this->builder->where('id', $id);

    }


    /**
     * @param $user_id
     * @return mixed
     */
    protected function user_id($user_id)
    {

        return $this->builder->where('user_id', $user_id);

    }


    /**
     * @param $status
     * @return mixed
     */
    protected function status($status)
    {

        return $this->builder->where('status', $status);

    }


    /**
     * @param $body
     * @return mixed
     */
    protected function body($body)
    {

        return $this->builder->where('body',  'like', "%$body%");

    }


    /**
     * @param $title
     * @return mixed
     */
    protected function title($title)
    {

        return $this->builder->where('title',  'like', "%$title%");

    }


    /**
     * @param $deadline
     * @return mixed
     */
    protected function deadline($deadline)
    {

        return $this->builder->where('deadline',  'like', "%$deadline%");

    }


    /**
     * @param $created_at
     * @return mixed
     */
    protected function created_at($created_at)
    {

        return $this->builder->where('created_at',  'like', "%$created_at%");

    }


    /**
     * @param $updated_at
     * @return mixed
     */
    protected function updated_at($updated_at)
    {

        return $this->builder->where('updated_at',  'like', "%$updated_at%");

    }


    /**
     * @param $order_by
     * @return mixed
     */
    protected function order_by($order_by)
    {

        isset($this->request['order_dir']) ? $order_dir = $this->request['order_dir'] : $order_dir = 'desc';

        return $this->builder->orderBy($order_by, $order_dir);

    }


}
