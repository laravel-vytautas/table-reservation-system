<?php

namespace App\Http\Requests\Table;

use App\Models\Reservation;
use App\Models\Table;
use App\Rules\MaxRestaurantUsersCount;
use App\Rules\MaxTableUsersCount;
use App\Rules\ReservationCheck;
use App\Rules\UniqueEmail;
use Illuminate\Foundation\Http\FormRequest;

class ReserveRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $usersCount = count($this->users??[]);
        return [
            "reserved_date" => [new ReservationCheck($this->route('table')), "required", "date"],
            "reserved_time" => "required|in:" . implode(',', Reservation::RESERVATION_TIMES),
            "users" => [
                new MaxTableUsersCount($this->route('table'),$usersCount),
                new MaxRestaurantUsersCount($this->route('table'), $usersCount, $this->reserved_date),
                new UniqueEmail(),
                "required",
                "array"
            ],
            "users.*.first_name" => "required|string|max:255",
            "users.*.last_name" => "required|string|max:255",
            "users.*.email" => "required|email:filter|string|max:255",
            "reserved_by.first_name" => "required|string|max:255",
            "reserved_by.last_name" => "required|string|max:255",
            "reserved_by.email" => "required|email:filter|string|max:255",
            "reserved_by.phone" => "required|string|max:255",
        ];
    }
}
