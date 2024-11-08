<?php

namespace App\Http\Controllers;

use App\Services\MikrotikService;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    protected $mikrotikService;

    public function __construct(MikrotikService $mikrotikService)
    {
        $this->mikrotikService = $mikrotikService;
    }

    public function get()
    {
        $query = '/queue/simple/print';
        $data = $this->mikrotikService->getData($query);
        $userName = env('MIKROTIK_USER');
        $entity = 'queue';

        return view('Admin', ['datas' => $data, 'userName' => $userName, 'action' => 'list', 'entity' => $entity]);
    }

    public function create()
    {
        // Retorna la vista del formulario de creación de usuario
        $relations = [
            'target' => $this->mikrotikService->getData('/interface/print'),
        ];
        $fields = [
            'write_fields' => ['name', 'comment', 'max-limit'],
            'option_fields' => ['target'],
            'boolean_fields' => ['disabled']
        ];
        return view(
            'Admin',
            [
                'entity' => 'queue',
                'action' => 'create',
                'fields' => $fields,
                'relations' => $relations,
            ]
        );
    }

    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'comment' => 'required|string|',
            'max-limit' => 'required|string',
            'target' => 'required|string',
            'disable' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'comment', 'max-limit', 'target', 'disable']);

        $response = $this->mikrotikService->create($data, '/queue/simple/add');

        if (isset($response['after']['ret'])) {
            return redirect()->route('mikrotik.queue.list')->with('mensaje', 'Usuario creado exitosamente');
        } else {
            return redirect()->route('mikrotik.queue.create')->with('mensaje', 'Error al crear el usuario: ');
        }
    }

    public function edit($id)
    {
        $query = '/queue/simple/print';
        $data = $this->mikrotikService->getById($id, $query);
        $relations = [
            'target' => $this->mikrotikService->getData('/interface/print'),
        ];
        $fields = [
            'write_fields' => ['name', 'comment', 'max-limit'],
            'option_fields' => ['target'],
            'boolean_fields' => ['disabled'],
            'read_fields' => ['invalid'],
        ];

        return view(
            'Admin',
            [
                'entity' => 'queue',
                'data' => $data,
                'action' => 'edit',
                'fields' => $fields,
                'relations' => $relations,
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $query = '/queue/simple/set';
        $data = $request->only(['name', 'comment', 'max-limit', 'target', 'disable']);

        $response = $this->mikrotikService->editUser($id, $data, $query);

        if ($response != []) {
            // Asegúrate de que $response esté definido antes de intentar acceder a él
            $message = isset($response['after']['message']) ? $response['after']['message'] : 'Error desconocido';
            return redirect()->route('mikrotik.address.edit', $id)->with('mensaje', $message);
        }


        return redirect()->route('mikrotik.queue.list', $id)->with('status', 'Usuario actualizado correctamente');
    }

    public function delete($id)
    {
        $response = $this->mikrotikService->deleteById($id, '/queue/simple/remove');

        return redirect()->route('mikrotik.queue.list', $id)->with('status', 'Usuario actualizado correctamente');
    }
}
