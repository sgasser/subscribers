<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Subscriber extends Model
{
    const DEFAULT_STATE = 'unconfirmed';

    protected $attributes = [
        'state' => self::DEFAULT_STATE,
    ];

    protected $fillable = [
        'email',
        'state'
    ];

    public function fields()
    {
        return $this->belongsToMany(Field::class)->using(FieldSubscriber::class)->withTimestamps();
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->request->all();

            if (!$this->id) {
                $this->fill($data);
            } else {
                // Don't update state
                unset($data['state']);

                $this->update($data);
            }
            $this->save();

            if ($request->has('fields')) {
                $this->updateOrCreateFields($request->get('fields'));
                $this->save();
            }

            return $this;
        });
    }

    protected function updateOrCreateFields(array $fields)
    {
        foreach ($fields as $title => $value) {
            // TODO: on many fields it's faster to get all fields with one sql query
            $field = Field::where('title', $title)->first();
            if (!$field) {
                throw ValidationException::withMessages(['fields.title' => ':title not exists']);
            }

            $data = ['value' => $value];

            Validator::make($data, [
                'value' => $field->type
            ])->validate();

            $this->fields()->attach($field->id, $data);
        }
    }
}
