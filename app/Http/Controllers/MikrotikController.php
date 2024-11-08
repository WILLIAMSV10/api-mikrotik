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
}
