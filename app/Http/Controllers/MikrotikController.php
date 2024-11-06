<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\MikrotikService;
use Illuminate\Http\JsonResponse;

class MikrotikController extends Controller
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

        return view('mainMenu', ['datas' => $data]);
    }

    public function getRouterData()
    {
        $query = '/interface/print'; // Un ejemplo de consulta para obtener interfaces
        $data = $this->mikrotikService->getData($query);

        return response()->json($data);
    }
}
