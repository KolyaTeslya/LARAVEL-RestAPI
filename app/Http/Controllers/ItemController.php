<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Jobs\ProcessItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;


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

    public function getWeatherWithRedis()
    {
        $cachedWeather = Redis::get('weather');

        if ($cachedWeather) {
            return json_decode($cachedWeather);
        }

        $response = Http::get('https://api.weather.com');

        Redis::setex('weather', 3600, $response->body());

        return $response->json();
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

        if (Gate::allows('update', $item)) {
            $item->update($request->all());
            return $item;
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json(null, 204);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->accessToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }


}
