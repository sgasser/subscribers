<?php

namespace App\Http\Requests;

use App\Rules\ValidEmailHost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', new ValidEmailHost()],
            'state' => ['string', Rule::in(['active', 'unsubscribed', 'junk', 'bounced', 'unconfirmed'])]
        ];
    }
}
