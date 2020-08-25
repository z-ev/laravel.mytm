<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
                'type' => 'project',
                'id' => $this->id,
                'attributes' => [
                    'status' => $this->status,
                    'title' => $this->title,
                    'body' => $this->body,
                    'deadline' => $this->deadline,
                    'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                ],
                'relationships' => [
                    'user' => [
                        'data' => [
                            'type' => 'user',
                            'user_id' => $this->user_id,
                        ],
                        'links' => [
                            'self' => route('users.show', $this->user_id)
                        ]
                    ]
                ],
                'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
                'links' => [
                    'self' => route('projects.show', $this->id),
                ]
            ]

        ];

    }


}
