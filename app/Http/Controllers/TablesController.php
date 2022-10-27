<?php

namespace App\Http\Controllers;

use App\Http\Requests\Table\ReserveRequest;
use App\Http\Requests\Table\StoreOrUpdate;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $query = Table::query()->with('reservations.reservedBy', 'restaurant', 'reservations.users');

        if ($request->boolean('free_tables')) {
            $query->whereDoesntHave('reservations');
        }

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->input('restaurant_id'));
        }

        if ($request->filled('place_count')) {
            $query->where('place_count', $request->input('place_count'));
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', "%" . $request->input('name') . "%");
        }

        if ($request->filled('search_reserved_by')) {
            $search = $request->input('search_reserved_by');
            $query = $query->whereHas('reservations.reservedBy', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('email', 'LIKE', "%" . $search . "%")
                        ->orWhere('phone', 'LIKE', "%" . $search . "%")
                        ->orWhereRaw('CONCAT(first_name," ",last_name) LIKE ?', ["%" . $search . "%"]);
                });
            });
        }

        return $query->paginate(50);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Table $table
     * @return Table
     */

    public function show(Table $table)
    {
        return $table->load('reservations.reservedBy', 'restaurant', 'reservations.users');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdate $request)
    {
        $table = Table::create($request->only("restaurant_id", "place_count", "name"));
        $table->restaurant->increment('table_count');
        return $table->load('reservations.reservedBy', 'restaurant', 'reservations.users');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Table $table
     * @return Table
     */
    public function update(StoreOrUpdate $request, Table $table)
    {
        $table->update($request->only("restaurant_id", "place_count", "name"));
        return $table->fresh()->load('reservations.reservedBy', 'restaurant', 'reservations.users');
    }

    public function reserve(ReserveRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $table = $request->input('table');

            $reservedUser = User::updateOrCreate(
                ['email' => $request->input('reserved_by.email')], [
                'first_name' => $request->input('reserved_by.first_name'),
                'last_name' => $request->input('reserved_by.last_name'),
                'email' => $request->input('reserved_by.email'),
                'phone' => $request->input('reserved_by.phone'),
            ]);
            $userIds[] = $reservedUser->id;

            $reservation = Reservation::create([
                "table_id" => $table->id,
                "reserved_by" => $reservedUser->id,
                "reserved_date" => $request->input("reserved_date"),
                "reserved_time" => $request->input("reserved_time"),
            ]);

            foreach ($request->input('users') as $user) {
                $user = User::updateOrCreate(
                    ['email' => $user['email']], [
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                ]);
                $userIds[] = $user->id;
            }
            $reservation->users()->sync($userIds ?? []);
            return $table->fresh()->load('reservations.reservedBy', 'restaurant', 'reservations.users');
        });
    }

    public function cancelReservation(Table $table, Reservation $reservation)
    {
        return DB::transaction(function () use ($table, $reservation) {
            $reservation->delete();
            $reservation->users()->sync([]);
            return $table->load('reservations.reservedBy', 'restaurant', 'reservations.users');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Table $table
     * @return array
     */
    public function destroy(Table $table)
    {
        return DB::transaction(function () use ($table) {
            foreach ($table->reservations as $reservation){
                $reservation->delete();
                $reservation->users()->sync([]);
            }
            $table->delete();
            return [];
        });
    }
}
