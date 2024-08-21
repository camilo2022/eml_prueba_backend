<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserRestoreRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserIndexCollection;
use App\Models\User;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index(UserIndexRequest $request)
    {
        try {
            /*
             * Obtiene la lista de usuarios, filtra por búsqueda si se proporciona, incluye usuarios eliminados y ordena
             * según la columna y dirección especificadas. Luego, paginamos los resultados.
            */
            $users = User::when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->withTrashed() // Incluye usuarios eliminados en los resultados
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage')); // Pagina los resultados

            return $this->successResponse(
                new UserIndexCollection($users),
                $this->getMessage('Success'),
                200
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            // Crea un nuevo usuario con los datos proporcionados en la solicitud
            $user = new User();
            $user->names = $request->input('names');
            $user->last_names = $request->input('last_names');;
            $user->number_phone = $request->input('number_phone');
            $user->email = $request->input('email');
            $user->save(); // Guarda el usuario en la base de datos

            // Retorna una respuesta exitosa con el usuario creado
            return $this->successResponse(
                $user,
                'El usuario fue registrado exitosamente.',
                201
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit($id)
    {
        try {
            // Busca el usuario por su ID
            $user = User::findOrFail($id);

            // Retorna una respuesta exitosa con el usuario encontrado
            return $this->successResponse(
                $user,
                'El usuario fue encontrado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            // Captura y maneja excepciones cuando no se encuentra el modelo
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            // Busca el usuario por su ID
            $user = User::findOrFail($id);
            // Actualiza los datos del usuario con la información proporcionada en la solicitud
            $user->names = $request->input('names');
            $user->last_names = $request->input('last_names');
            $user->number_phone = $request->input('number_phone');
            $user->email = $request->input('email');
            $user->save(); // Guarda los cambios en la base de datos

            // Retorna una respuesta exitosa con el usuario actualizado
            return $this->successResponse(
                $user,
                'El usuario fue actualizado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            // Captura y maneja excepciones cuando no se encuentra el modelo
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Captura y maneja excepciones relacionadas con la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete(UserDeleteRequest $request)
    {
        try {
            // Busca el usuario por su ID y lo elimina
            $user = User::findOrFail($request->input('id'))->delete();

            // Retorna una respuesta exitosa indicando que el usuario fue eliminado
            return $this->successResponse(
                $user,
                'El usuario fue eliminado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            // Captura y maneja excepciones cuando no se encuentra el modelo
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function restore(UserRestoreRequest $request)
    {
        try {
            // Busca el usuario eliminado por su ID y lo restaura
            $user = User::withTrashed()->findOrFail($request->input('id'))->restore();

            // Retorna una respuesta exitosa indicando que el usuario fue restaurado
            return $this->successResponse(
                $user,
                'El usuario fue restaurado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            // Captura y maneja excepciones cuando no se encuentra el modelo
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            // Captura y maneja cualquier otra excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
