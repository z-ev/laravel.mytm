<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\Filter;
use App\Filters\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param ProjectFilter $filters
     * @return TaskCollection
     * @throws Filter
     */
    public function index(ProjectFilter $filters)
    {

        $paginate = $filters->getPaginate();

        try {

            $tasks = Task::filter($filters)->paginate($paginate);

        } catch (QueryException $exception) {

            throw new Filter();

        }

        return new TaskCollection($tasks);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param TaskCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCreateRequest $request)
    {

        $body = request('body');

        $deadline = Carbon::parse(request('deadline'));

        $project = Project::find(request('project_id'));

        if (!isset($project)) { $error = 'Указанный проект не найден.';}

        if ($project->user_id != auth()->user()->id) { $error = 'У вас нет прав.';}

        if (isset($error)) {

            return response()->json(['data'=>['error'=>$error]],400);

        } else {

            $task = new Task();

            $task->user_id = auth()->user()->id;

            $task->project_id = request('project_id');

            $task->title = request('title');

            $task->status = '1';

            if (isset($body)) {

                $task->body = $body;

            } else {

                $task->body = '';

            }

            if (isset($deadline)) {

                $task->deadline = $deadline;

            } else {

                $task->deadline = Carbon::parse('2020-08-23');

            }

            $task->save();

            return new TaskResource($task);

        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {

        if (!$task) {

            return response()->json([

                'message'=>'post not found'

                ],404);
        }

        return new TaskResource($task);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {

        if (!$task) {

            $error = 'Задачи с таким id не существует';
        }

        if ($task->user_id != auth()->user()->id) {

            $error = 'У вас нет прав чтобе изменить задачу.';

        }

        if (isset($error)) {

            return response()->json(['data' => ['error' => $error]], 400);

        } else {

            request('title') && $task->title = request('title');

            request('body') && $task->body = request('body');

            request('deadline') && $task->deadline = date(request('deadline'));

            $task->status = request('status') ?? '1';

            $task->updated_at = now();

            $task->save();

            return new TaskResource($task);

        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        if ($task->user_id != auth()->user()->id) {

        $error = 'У вас нет прав чтобы удалить задачу.';

        }

        if (isset($error)) {

            return response()->json(['data' => ['error' => $error]], 400);

        } else {

            $task->delete();

            return response()->json([
                'data' => [
                    'message' => 'Задача удалена'
                ],
                'links' => [
                    'self' => route('tasks.index'),
                ]
            ], 200);

        }

    }

}
