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
}
