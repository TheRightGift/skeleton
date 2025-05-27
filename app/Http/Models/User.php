<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'two_factor_secret', 'two_factor_enabled',
    ];

    protected $hidden = [
        'password', 'two_factor_secret',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function bankDetails()
    {
        return $this->hasOne(BankDetail::class);
    }
}