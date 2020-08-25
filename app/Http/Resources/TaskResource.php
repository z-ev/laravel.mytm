<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
                'type' => 'task',
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
                            'user_id' => $this->user_id,
                        ],
                        'links' => [
                            'self' => route('users.show', $this->user_id),
                        ]
                    ],
                    'project' => [
                        'data' => [
                            'project_id' => $this->project_id,
                        ],
                        'links' => [
                            'self' => route('projects.show', $this->project_id),
                        ]

                    ]
                ]
            ],
            'links' => [
                'self' => route('tasks.show', $this->id),
            ]

        ];

    }


}
