<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class UserIndexCollection extends ResourceCollection
{
     /**
     * OJO SI VOY A ENVIAR UN ARRAY DE MUCHOS DATOS SE USAN LAS COLLECCIONES PARA DARLE FORMATO A LA RESPUESTA
     * usarla de esta manera permite darle un formato mas definido a las variables de respuesta
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'users' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'names' => $user->names,
                    'last_names' => $user->last_names,
                    'number_phone' => $user->number_phone,
                    'email' => $user->email,
                    'created_at' => $this->formatDate($user->created_at),
                    'updated_at' => is_null($user->updated_at) ? $user->updated_at : $this->formatDate($user->updated_at),
                    'deleted_at' => is_null($user->deleted_at) ? $user->deleted_at : $this->formatDate($user->deleted_at),
                ];
            }),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
