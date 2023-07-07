<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'description'];

    public static function getItem($id)
    {
        return self::findOrFail($id);
    }

    public static function createItem($data)
    {
        return self::create($data);
    }

    public function updateItem($data)
    {
        $this->fill($data)->save();
        return $this;
    }

    public function deleteItem()
    {
        $this->delete();
    }
}


