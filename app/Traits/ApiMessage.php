<?php

namespace App\Traits;

trait ApiMessage
{
	private $messages = [
		'Success' => 'Operación completada con éxito.',
		'ModelNotFoundException' => 'No se encontraron resultados para la búsqueda.',
		'QueryException' => 'Error en la base de datos, por favor, inténtelo de nuevo más tarde.',
		'Exception' => 'Se ha producido un error inesperado, por favor, inténtelo de nuevo más tarde.',
    ];

    public function getMessage($key) : string
	{
        // Valida si existe el tipo de error y devuelve un mensaje, en caso de que no lo encuentra envia un mensaje predeterminado.
        return isset($this->messages[$key]) ? $this->messages[$key] : 'Mensaje no encontrado';
    }
}
