<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'avatar',
        'user_id',
    ];

    public function getAvatarUrl()
    {
        return Storage::url($this->avatar);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class);
    }

    public function totalPayments()
    {
        return $this->payments->map(function ($i){
            return $i->amount;
        })->sum();
    }

    public function totalSales()
    {
        return $this->orders->map(function ($i){
            return $i->total();
        })->sum();
    }

    public function totalDiscounts()
    {
        return $this->orders->map(function ($i){
            return $i->discount;
        })->sum();
    }

    public function balancePayments()
    {
        return $this->totalSales() - $this->totalDiscounts() - $this->totalPayments();
    }
}
