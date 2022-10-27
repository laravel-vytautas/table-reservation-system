<?php

namespace App\Rules;

use App\Models\Restaurant;
use App\Models\Table;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MaxTableUsersCount implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param $table
     * @param $usersCount
     */
    public function __construct(private $table, private $usersCount)
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
        if($this->table->place_count < $this->usersCount) {
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
        return 'The table is too small for this amount of people.';
    }
}
