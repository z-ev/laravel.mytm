<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\Filter;
use App\Exceptions\UserNotSignUp;
use App\Filters\ProjectFilter;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;

use App\Http\Resources\ProjectResource;
use App\Models\Project;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;




class ProjectController extends Controller
{

    /**
     * Информация по всем проектам пользователя
     *
     * (get) /projects/
     *
     * @param ProjectFilterRequest $request
     * @param ProjectFilter $filters
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws Filter
     */
    public function index(ProjectFilter $filters)
    {
        $paginate = $filters->getPaginate();

        try { $projects = Project::filter($filters)->paginate($paginate); } catch (QueryException $exception) {
            throw new Filter();
        }

        return ProjectResource::collection($projects);

    }


    /**
     * Создаем проект
     *
     * (post) /projects/{id}
     *
     * @param ProjectCreateRequest $request
     * @return ProjectResource
     */
    public function store(ProjectCreateRequest $request)
    {
        $project = new Project();

        $title = request('title');
        $body = request('body');


        $deadline = Carbon::parse(request('deadline'));
        $project->user_id = auth()->user()->id;

        $project->title = $title;

        if (isset($body)) {
            $project->body = $body;
        } else $project->body = '';

        if (isset($deadline)) {
            $project->deadline = $deadline;

        } else $project->deadline = Carbon::parse('2020-08-23');

        $project->status = '1';

        $project->save();

        return new ProjectResource($project);
    }


    /**
     * Информация о проекте по ИД
     *
     * (get) /projects/{id}
     *
     * @param Project $project
     * @return ProjectResource|\Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        return new ProjectResource($project);
    }


    /**
     * Обновить проект
     *
     * (patch) /projects
     *
     * @param ProjectUpdateRequest $request
     * @param $id
     * @return ProjectResource
     * @throws UserNotSignUp
     */
    public function update(ProjectUpdateRequest $request, $id)
    {
        $project = Project::findOrFail($id);

            if (isset($request['title'])) {
                $project->title = $request['title'];
            }

            if (isset($request['body'])) {
                $project->body = $request['body'];
            }

            if (isset($request['status'])) {
                $project->status = $request['status'];
            }

            if (isset($request['deadline'])) {
                $project->deadline = Carbon::parse($request['deadline']);
            } else $project->deadline = Carbon::parse('2020-08-23 16:53:23');

            $project->save;

            return new ProjectResource($project);

    }


    /**
     * Удаляем проект пользователя по ИД
     *
     * (delete) /projects/{id}
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Project $project)
    {

        if (!$project) {
           $error = 'Такого проекта не существует';
        }

        if ($project->user_id != auth()->user()->id) {
            $error = 'У вас нет прав для удаления проекта';
        } else {

            $tasks = Task::where('project_id', $project->id)->first();

            if ($tasks) {
                $error = 'Не возможно удалить проект та как по нему поставлены задачи';
            }

        }

        if (isset($error)) {

            return response()->json(['data'=>['error'=>$error]],400);

        } else {

            $project->delete();
            return response()->json([
                'data'=>[
                    'message'=>'Проект успешно удален!'
                ],
                'links' => [
                'self' => route('projects.index'),
                ]
            ],200);

        }
    }


    /**
     * Удаляем все задачи пользователя по ИД проекта
     *
     * (delete) /projects/{id}/kill
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function kill($id)
    {
        $project = Project::findOrFail($id);

        if (!$project) {$error = 'Такого проекта не существует';}
        if ($project->user_id != auth()->user()->id) { $error = 'У вас нет прав для удаления проекта';}


        if (isset($error)) {

            return response()->json(['data'=>['error'=>$error]],400);

        } else {

            $tasks = Task::where('project_id', $project->id)->get();

            if ($tasks) {
                foreach ($tasks as $task)
                {
                    $task->delete();
                }
                $this->Destroy($project);
            }

            return response()->json(['data'=>['message'=>'Проект успешно удален вместо со всеми задачами!'],'links' => [
                'self' => route('projects.index'),
            ]],200);

        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

}
