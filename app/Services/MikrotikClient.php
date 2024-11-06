<?php

namespace App\Services;

class MikrotikClient
{
    protected $host;
    protected $user;
    protected $password;
    protected $socket;

    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect()
    {
        $this->openConnection();

        // Login
        $this->write('/login');
        $response = $this->read();

        // Manejo del desafío
        if (isset($response[1]) && strpos($response[1], '=ret=') !== false) {
            $challenge = substr($response[1], strpos($response[1], '=ret=') + 5);
        } else {
            throw new \Exception("No se recibió un desafío válido de la API.");
        }

        // Asegúrate de que $challenge sea un valor hexadecimal
        if (!preg_match('/^[0-9a-fA-F]+$/', $challenge)) {
            throw new \Exception("El desafío no es válido: $challenge");
        }

        $challenge_bin = hex2bin($challenge);

        if ($challenge_bin === false) {
            throw new \Exception("Challenge no es un valor hexadecimal válido.");
        }

        // Combinar el byte cero + contraseña + desafío en binario
        $combined = $this->password . $challenge_bin;

        // Calculate hashed password
        // Calcular el hash MD5 de la combinación
        $hash = md5($combined);
        // $hash = md5(chr(0) . $this->password . $challenge);

        if (!preg_match('/^[0-9a-fA-F]+$/', $hash)) {
            throw new \Exception("El hash no es válido: $hash");
        }

        // Send login with hashed password
        $this->write('/login', [
            '=name=' . $this->user,
            '=response=00' . $hash,
        ]);

        $response = $this->read();
        dd($response);
        if (isset($response[0]) && strpos($response[0], '!done') === false) {
            throw new \Exception("Login failed.");
        }
    }

    private function openConnection()
    {
        $this->socket = fsockopen($this->host, 8728, $errno, $errstr, 5);
        if (!$this->socket) {
            throw new \Exception("Error connecting to Mikrotik API: $errstr ($errno)");
        }
    }

    public function write($command, $attributes = [])
    {
        $this->sendWord($command);
        foreach ($attributes as $attribute) {
            if (strpos($attribute, '=') === false) {
                throw new \Exception("Formato de atributo no válido: $attribute");
            }
            $this->sendWord($attribute);
        }
        fwrite($this->socket, chr(0));
    }

    public function read()
    {
        $response = [];
        while (true) {
            $word = $this->readWord();
            if ($word === '') {
                break;
            }

            $response[] = $word;
        }
        return $response;
    }

    private function sendWord($word)
    {
        $len = strlen($word);
        fwrite($this->socket, chr($len));
        fwrite($this->socket, $word);
    }

    private function readWord()
    {
        $length = ord(fread($this->socket, 1));

        if ($length == 0) {
            return '';
        }
        $length = $this->getWordLength($length);

        return fread($this->socket, $length);
    }

    private function getWordLength($length)
    {
        if ($length & 0x80) {
            $length &= ~0x80;
            $length = ($length << 8) + ord(fread($this->socket, 1));
        }
        return $length;
    }

    public function close()
    {
        fclose($this->socket);
    }
}
