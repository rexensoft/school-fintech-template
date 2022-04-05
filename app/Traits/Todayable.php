<?php

namespace App\Traits;

trait Todayable{
    public function scopeToday($query) {
        return $query->whereDate('created_at', now());
    }
}