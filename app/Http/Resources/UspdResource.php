<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UspdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $installationObjectShowRoute = $request->routeIs('installation-objects.show');
        $UspdSimCardCreateRoute = $request->routeIs('uspds.sim-cards.create');

        $condition = $installationObjectShowRoute || $UspdSimCardCreateRoute;

        return [
            'id' => $this->id,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'lan_ip' => $this->when(! $condition, $this->lan_ip),
            'simCards' => SimCardResource::collection($this->whenLoaded('simCards')),
            'installationObject' => $this->whenExistsLoaded(
                'installationObject',
                new InstallationObjectResource($this->installationObject)
            ),
        ];
    }
}
