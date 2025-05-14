<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'card_number', 'card_holder', 'expiry_date', 'cvv'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
