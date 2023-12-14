<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Filename implements ValidationRule
{
    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $filenameWithExtension = $value->getClientOriginalName();
        $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
        if ($filename !== $this->table) {
            $fail('The file name is incorrect.');
        }
    }
}
