<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            'data' => [
                'type' => 'users',
                'id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'projects' => ProjectResource::collection($this->whenLoaded('projects')),
                    'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
                ],

            ],
            'links' => [
                'self' => route('users.show', $this->id),
            ]

        ];

    }


}
