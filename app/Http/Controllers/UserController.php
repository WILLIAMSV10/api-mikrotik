<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\MikrotikService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $mikrotikService;

    public function __construct(MikrotikService $mikrotikService)
    {
        $this->mikrotikService = $mikrotikService;
    }

    public function getUsers()
    {
        $query = '/user/print';
        $data = $this->mikrotikService->getData($query);
        $userName = env('MIKROTIK_USER');
        $entity = 'user';

        return view('Admin', ['datas' => $data, 'userName' => $userName, 'action' => 'list', 'entity' => $entity]);
    }

    public function create()
    {
        // Retorna la vista del formulario de creación de usuario
        $relations = [
            'group' => $this->mikrotikService->getData('/user/group/print'),
        ];
        $fields = [
            'write_fields' => ['comment', 'name', 'password'],
            'option_fields' => ['group'],
        ];
        return view(
            'Admin',
            [
                'entity' => 'user',
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
            'password' => 'required|string|min:6',
            'group' => 'required|string',
            'comment' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['name', 'password', 'group', 'comment']);

        try {
            $response = $this->mikrotikService->create($data, '/user/add');
            return redirect()->route('mikrotik.user.list')->with('mensaje', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('mikrotik.user.create')->with('mensaje', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        $query = '/user/print';
        $data = $this->mikrotikService->getById($id, $query);
        $relations = [
            'group' => $this->mikrotikService->getData('/user/group/print'),
        ];
        $fields = [
            'write_fields' => ['comment', 'name', 'address'],
            'option_fields' => ['group'],
            'boolean_fields' => ['expired', 'disabled'],
            'read_fields' => ['last-logged-in'],
        ];

        return view(
            'Admin',
            [
                'entity' => 'user',
                'data' => $data,
                'action' => 'edit',
                'fields' => $fields,
                'relations' => $relations,
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $query = '/user/set';
        $data = $request->only(['name', 'comment', 'group', 'address', 'disabled']);

        $response = $this->mikrotikService->editUser($id, $data, $query);
        if ($response != []) {
            // Asegúrate de que $response esté definido antes de intentar acceder a él
            $message = isset($response['after']['message']) ? $response['after']['message'] : 'Error desconocido';
            return redirect()->route('mikrotik.user.edit', $id)->with('mensaje', $message);
        }


        return redirect()->route('mikrotik.user.list', $id)->with('status', 'Usuario actualizado correctamente');
    }
}
