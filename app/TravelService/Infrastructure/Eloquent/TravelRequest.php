<?php

namespace App\TravelService\Infrastructure\Eloquent;

use Database\Factories\TravelRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    use HasFactory;

    protected $table = 'travel_requests';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'user_id',
        'applicant_name',
        'destination',
        'departure_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public static function newFactory()
    {
        return TravelRequestFactory::new();
    }
}
