<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Jobs\ProcessItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
class ItemController extends Controller
{
    public function index()
    {
        return Cache::remember('items', 3600, function () {
            return Item::all();
        });
    }

    public function getWeather()
    {
        return Cache::remember('weather', 3600, function () {
            $response = Http::get('https://api.weather.com');
            return $response->json();
        });
    }

    public function show($id)
    {
        return Item::findOrFail($id);
    }


    public function store(Request $request)
    {
        $item = Item::create($request->all());

        ProcessItem::dispatch($item)->onQueue('process_items');

        return $item;
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->all());
        return $item;
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }


}
