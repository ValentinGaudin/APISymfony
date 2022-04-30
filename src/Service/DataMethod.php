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

    public function getDataFromRequest(Request $request): array
    {
        $data = [];

        foreach ($request->request as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }

    public function hydrate(array $data, Object $class)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($class, $method)) {
                $class->$method($value);
            }
        }
        return $class;
    }
}
