<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\UserNotSignUp;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


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
     * @param $id
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
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

            if (isset($request['deadline'])) {
                $project->deadline = Carbon::parse($request['deadline']);
            } else $project->deadline = Carbon::parse('2020-08-23 16:53:23');

            $project->save;

            return new ProjectResource($project);

    }

    /**
     * Удаляем все задачи пользователя по ИД проекта
     *
     * (delete) /projects/{id}
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::Find($id);

        if (!$project) {
            $error = 'Такого проекта не существует';
            return response()->json(['data' => ['error' => $error]], 400);
        }
        if ($project->user_id != auth()->user()->id) {
            $error = 'У вас нет прав для удаления проекта';
        } else {

            /*$tasks = Task::where('project_id', $id)->first();
            if ($tasks) {
                $error = 'Не возможно удалить проект та как по нему поставлены задачи';
            }*/
        }
        if (isset($error)) {
            return response()->json(['data' => ['error' => $error]], 400);
        } else {

            $project->delete();
            return response()->json(['data' => ['message' => 'Проект успешно удален!'], 'links' => [
                'self' => route('api.projects.index'),
            ]], 200);
        }
    }
}
