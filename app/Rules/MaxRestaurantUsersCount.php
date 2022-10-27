<?php

namespace App\Rules;

use App\Models\ReservationUser;
use Illuminate\Contracts\Validation\Rule;

class MaxRestaurantUsersCount implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param $table
     * @param $usersCount
     * @param $reservationDate
     */
    public function __construct(private $table, private $usersCount, private $reservationDate)
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
       $count = ReservationUser::query()
           ->leftJoin('reservations','reservations.id','reservation_users.reservation_id')
           ->where('reservations.reserved_date', $this->reservationDate)
           ->count('user_id');

        if (($count + $this->usersCount) > $this->table->restaurant->max_people_count) {
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
        return 'Reached max people count in restaurant.';
    }
}
