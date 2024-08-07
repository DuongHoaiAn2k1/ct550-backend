<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HasChar implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Kiểm tra xem mật khẩu có chứa cả chữ cái và số không
        return preg_match('/[a-zA-Z]/', $value) && preg_match('/[0-9]/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mật khẩu phải chứa cả chữ cái và số.';
    }
}
