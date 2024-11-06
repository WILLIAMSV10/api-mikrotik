<?php

namespace App\Services;

class MikrotikService
{
    protected $client;

    public function __construct()
    {
        $this->client = new MikrotikClient(
            env('MIKROTIK_HOST'),
            env('MIKROTIK_USER'),
            env('MIKROTIK_PASSWORD')
        );
        $this->client->connect();
    }

    public function getInterfaces()
    {
        // $this->client->write('/interface/print');
        // return $this->client->read();
    }

    public function __destruct()
    {
        $this->client->close();
    }
}
