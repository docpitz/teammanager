<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class DifferentIgnoreCaseRule implements Rule
{
    protected $other;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($otherParam)
    {
        $this->other = $otherParam;
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
        return strcasecmp($value, $this->other) != 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute and :other must be different.";
    }
}
