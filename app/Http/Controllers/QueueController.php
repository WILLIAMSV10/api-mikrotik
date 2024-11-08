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
        dd($data);

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
}
