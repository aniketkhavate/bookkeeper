<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEntry extends Model
{
    use HasFactory;
    protected $table = 'service_entries';
    protected $fillable = [
        'customer_id',
        'service_id',
        'user_id',
        'description',
        'rate',
        'quantity',
        'total_bill',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getServiceEntries(array $where = [])
    {
        return ServiceEntry::with(['customer:id,name,email,phone', 'service:id,name', 'user:id,name'])->where($where)->orderBy('id', 'desc')->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
