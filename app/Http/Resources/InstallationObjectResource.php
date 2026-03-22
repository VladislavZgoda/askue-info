<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstallationObjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $showRoute = $request->routeIs('installation-objects.show');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->when(! $showRoute, $this->address),
            'meters' => MeterResource::collection($this->whenLoaded('meters')),
            'uspds' => UspdResource::collection($this->whenLoaded('uspds')),
        ];
    }
}
