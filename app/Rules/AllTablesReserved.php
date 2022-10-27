<?php

namespace App\Rules;

use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AllTablesReserved implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param $table
     * @param $usersCount
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
        if($value) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'All tables reserved.';
    }
}
