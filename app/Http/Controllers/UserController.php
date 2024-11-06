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

        return view('Admin', ['datas' => $data, 'userName' => $userName, 'action' => 'list']);
    }

    public function editUser($id)
    {
        $query = '/user/print';
        $data = $this->mikrotikService->getById($id, $query);
        $groups = $this->mikrotikService->getData('/user/group/print');
        $relations = [
            'group' => 'groups',

        ];
        $fields = [
            'write_fields' => ['comment', 'name', 'address'],
            'option_fields' => ['expired', 'disabled', 'group',],
            'read_fields' => ['last-logged-in'],
        ];

        return view(
            'Admin',
            [
                'data' => $data,
                'action' => 'edit',
                'fields' => $fields
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $query = '/user/set';
        $data = $request->only(['name', 'password', 'group']);
        $response = $this->mikrotikService->editUser($id, $data, $query);

        return redirect()->route('mikrotik.user.edit', $id)->with('status', 'Usuario actualizado correctamente');
    }
}
