<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PastQuestionResource extends JsonResource
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
            'subject' => $this->subject,
            'level' => $this->level,
            'year' => $this->year,
            'category' => $this->category,
            'file_size' => $this->file_size,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at,
        ];
    }
}
