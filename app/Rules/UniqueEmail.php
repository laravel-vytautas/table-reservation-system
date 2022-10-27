<?php

namespace App\Rules;

use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $emails = [];
        foreach ($value as $user){
            $emails[] = $user['email'];
        }
        if (count($emails) === count(array_unique($emails))) {
           return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Emails has to be unique in users list.';
    }
}
