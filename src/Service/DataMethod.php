<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class DataMethod
{
    public function cleanData(array $data): array
    {
        $data = array_map('trim', $data);
        $data = array_map('stripslashes', $data);

        return $data;
    }

    public function getData(Request $request): array
    {
        $data = [];

        foreach ($request->request as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut
            $method = 'set' . ucfirst($key);

            // Si le setter correspondant existe.
            if (method_exists($this, $method)) {
                // On appelle le setter
                $this->$method($value);
            }
        }
    }
}
