<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    /**
     * Validate input data
     *
     * @param array $data
     * @param array $rules
     * @return void
     * @throws \Exception
     */
    protected function validate(array $data, array $rules): void
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    }

    /**
     * Format data before returning
     *
     * @param mixed $data
     * @return mixed
     */
    protected function formatData($data)
    {
        return $data;
    }
}