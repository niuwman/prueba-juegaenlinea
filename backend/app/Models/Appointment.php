<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = "public.appointments";

    protected $fillable = [
        'customer_name',
        'customer_email',
        'appointment_at'
    ];

    protected $casts = [
        'appointment_at' => 'datetime',
    ];
}