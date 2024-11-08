<?php

namespace App\Http\Controllers;

use App\Services\MikrotikService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $mikrotikService;

    public function __construct(MikrotikService $mikrotikService)
    {
        $this->mikrotikService = $mikrotikService;
    }

    public function get()
    {
        $query = '/ip/address/print';
        $data = $this->mikrotikService->getData($query);
        $userName = env('MIKROTIK_USER');
        $entity = 'address';

        return view('Admin', ['datas' => $data, 'userName' => $userName, 'action' => 'list', 'entity' => $entity]);
    }

    public function create()
    {
        // Retorna la vista del formulario de creación de usuario
        $relations = [
            'interface' => $this->mikrotikService->getData('/interface/print'),
        ];
        $fields = [
            'write_fields' => ['address', 'network'],
            'option_fields' => ['interface'],
            'boolean_fields' => ['disabled']
        ];
        return view(
            'Admin',
            [
                'entity' => 'address',
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
            'address' => 'required|string|max:255',
            'interface' => 'required|string|',
            'disable' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['address', 'interface', 'disable', 'network']);

        $response = $this->mikrotikService->create($data, '/ip/address/add');

        if ($response == []) {
            return redirect()->route('mikrotik.address.list')->with('mensaje', 'Usuario creado exitosamente');
        } else {
            return redirect()->route('mikrotik.address.create')->with('mensaje', 'Error al crear el usuario: ');
        }
    }

    public function edit($id)
    {
        $query = '/ip/address/print';
        $data = $this->mikrotikService->getById($id, $query);
        $relations = [
            'interface' => $this->mikrotikService->getData('/interface/print'),
        ];
        $fields = [
            'write_fields' => ['address', 'network'],
            'option_fields' => ['interface'],
            'boolean_fields' => ['disabled'],
            'read_fields' => ['invalid'],
        ];

        return view(
            'Admin',
            [
                'entity' => 'address',
                'data' => $data,
                'action' => 'edit',
                'fields' => $fields,
                'relations' => $relations,
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $query = '/ip/address/set';
        $data = $request->only(['address', 'network', 'interface', 'disabled']);

        $response = $this->mikrotikService->editUser($id, $data, $query);

        if ($response != []) {
            dd($response);
            // Asegúrate de que $response esté definido antes de intentar acceder a él
            $message = isset($response['after']['message']) ? $response['after']['message'] : 'Error desconocido';
            return redirect()->route('mikrotik.address.edit', $id)->with('mensaje', $message);
        }


        return redirect()->route('mikrotik.address.list', $id)->with('status', 'Usuario actualizado correctamente');
    }
}
