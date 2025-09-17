<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['name','email', 'password', 'role'];

    public function getRoleNameAttribute()
{
    return match ($this->role) {
        1 => 'Admin',
        2 => 'User',
        default => 'Tidak Diketahui',
    };
}

    public function email_username_only()
{
    return explode('@', $this->email)[0];
}


}
