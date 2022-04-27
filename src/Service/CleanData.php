<?php

namespace App\Service;

class CleanData
{
    public function cleanData(array $data): array
    {
        $data = array_map('trim', $data);
        $data = array_map('stripslashes', $data);

        return $data;
    }
}