<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JsonRequest extends FormRequest
{
    public function wantsJson(): true
    {
        return true;
    }

    public function expectsJson(): true
    {
        return true;
    }
}
