<?php

namespace App\Http\Controllers;

use App\Http\Requests\Restaurant\StoreOrUpdate;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        $query = Restaurant::query();
        return $query->with('table')->paginate(50);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return Restaurant
     */
    public function show(Restaurant $restaurant)
    {
        return $restaurant;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdate $request)
    {
        $restaurant = Restaurant::create($request->except('table_count'));
        return $restaurant;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return Restaurant
     */
    public function update(StoreOrUpdate $request, Restaurant $restaurant)
    {
        $restaurant->update($request->except('table_count'));
        return $restaurant->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return array
     */
    public function destroy(Restaurant $restaurant)
    {
        return DB::transaction(function () use ($restaurant) {
            foreach($restaurant->tables as $table){
                $table->delete();
                foreach($table->reservations as $reservation){
                    $reservation->delete();
                    $reservation->users()->sync([]);
                }
            }
            $restaurant->delete();
        return [];
        });
    }
}
