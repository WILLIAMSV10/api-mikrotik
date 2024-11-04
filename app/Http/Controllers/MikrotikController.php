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

    public function getInterfaces(): JsonResponse
    {
        $interfaces = $this->mikrotikService->getInterfaces();
        return response()->json(['message' => 'Conexión exitosa con Mikrotik']);
    }
}
