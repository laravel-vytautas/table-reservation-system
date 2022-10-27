<?php

namespace App\Http\Requests\Table;

use App\Models\Reservation;
use App\Models\Table;
use App\Rules\AllTablesReserved;
use App\Rules\MaxRestaurantUsersCount;
use App\Rules\MaxTableUsersCount;
use App\Rules\ReservationCheck;
use App\Rules\UniqueEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

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
        $table = null;
            if ($this->boolean("first_free_table")) {
                $table = Table::query()->whereDoesntHave('reservations')->first();

                if (!$table) {
                    return ["first_free_table" => [new AllTablesReserved()]];
                }
            }

            if (!$table){
                $table = Table::where('id',$this->table_id)->first();
                if(!$table){
                    return [
                        "table_id" => "required|exists:tables,id",
                        "first_free_table" => ["required",new AllTablesReserved()]
                    ];
                }
            }
            $this->merge(['table' => $table]);

        $usersCount = count($this->users??[]);
        return [
            "first_free_table" => ["required","boolean"],
            "table_id" => [new RequiredIf(!$this->boolean("first_free_table")), "exists:tables,id"],
            "reserved_date" => [new ReservationCheck($table), "required", "date"],
            "reserved_time" => "required|in:" . implode(',', Reservation::RESERVATION_TIMES),
            "users" => [
                new MaxTableUsersCount($table,$usersCount),
                new MaxRestaurantUsersCount($table, $usersCount, $this->reserved_date),
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
