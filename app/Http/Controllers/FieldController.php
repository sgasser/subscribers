<?php

namespace App\Http\Controllers;

use App\Field;
use App\Http\Requests\FieldRequest;

class FieldController extends Controller
{
    public function store(FieldRequest $request)
    {
        $field = new Field($request->request->all());
        $field->save();

        return $field;
    }
}
