<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'price' => $this->price,
            'instructor_name' => $this->instructor_name,
            'is_published' => $this->is_published,
            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
            'created_at' => $this->created_at,
        ];
    }
}
