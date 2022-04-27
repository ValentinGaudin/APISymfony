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
}