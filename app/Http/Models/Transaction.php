<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['wallet_id', 'amount', 'type', 'status', 'qr_code_key'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}