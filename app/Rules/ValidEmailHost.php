<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidEmailHost implements Rule
{
    public function passes($attribute, $value)
    {
        if (strpos($value, '@') !== false) {
            list($user, $domain) = explode('@', $value);

            return gethostbyname($domain) !== $domain;
        }
    }

    public function message()
    {
        return 'The domain of the mail not exists.';
    }
}
