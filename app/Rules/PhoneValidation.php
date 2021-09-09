<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        dd($value, $attribute);
         $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneUtil->parse($value);
            return true;
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.custom.phone.error');
    }
}
