<?php

namespace Nikservik\Subscriptions\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Nikservik\Subscriptions\Models\Payment;
use Nikservik\Subscriptions\TranslatableField;
use Spatie\Activitylog\Traits\LogsActivity;

class Subscription extends Model
{
    use LogsActivity;

    protected $fillable = [
        'slug', 'name', 'price', 'currency', 'period', 'prolongable', 
    ];

    protected $casts = [
        'features' => 'array',
        'availability' => 'array',
        'texts' => 'array',
        'last_transaction_date' => 'datetime',
        'next_transaction_date' => 'datetime',
        'name' => TranslatableField::class,
    ];

    protected static $logAttributes = ['last_transaction_date', 'next_transaction_date', 'status'];
    protected static $logOnlyDirty = true;

    
    public function __construct(array $attributes = [])
    {
        if (! config('subscriptions.log.subscriptions'))
            $this->disableLogging();
        parent::__construct($attributes);
    }

    public function getFeaturesAttribute($value)
    {
        return is_null($value) ? [] : json_decode($value, true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isEndless()
    {
        return $this->period == 'endless';
    }

    public function isPaid()
    {
        return $this->price > 0;
    }

    public function isTrial()
    {
        return ! $this->isEndless() and ! $this->prolongable and $this->price == 0;
    }
}
