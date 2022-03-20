<?php

namespace App\Traits\Models;

trait Searchable{
    public function scopeSearch($query, $search) {
        $first  = $query->first();

        if(!$first) return $query;

        $first  = collect($first->getOriginal());
        $keys   = $first->keys();
        $fields = $keys;
        $search = collect(explode(':', $search, 2));
        
        if(isset($search[1])) {
            $fields = collect(explode(',', (string) $search[0]));
            $search = $search[1];
        } else {
            $search = $search[0];
        }

        $result = $query->where(fn($query) => (
            $fields->each(function($item) use($keys, $query, $search) {
                $item = trim($item);
                if(!$keys->some($item)) return;
                $query->orWhere($item, 'LIKE', "%$search%");
            })
        ));

        return $result;
    }
}