<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;


class MikrotikService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'host' => env('MIKROTIK_HOST'),
            'user' => env('MIKROTIK_USER'),
            'pass' => env('MIKROTIK_PASSWORD'),
        ]);
    }

    public function getData($query)
    {
        $query = new Query($query);
        return $this->client->query($query)->read();
    }

    public function getById($id, $query)
    {
        $query = (new Query($query))
            ->where('.id', $id);

        return $this->client->query($query)->read()[0];
    }

    public function editUser($id, array $newData, $query)
    {
        // Crear la consulta para actualizar los datos del usuario
        $query = (new Query($query))
            ->equal('.id', $id); // especificar el ID del usuario que queremos editar

        // Agregar los campos a modificar en la consulta
        foreach ($newData as $field => $value) {
            if ($value !== null) {
                $query->equal($field, $value);
            }
        }

        // Ejecutar la consulta
        return $this->client->query($query)->read();
    }
}
