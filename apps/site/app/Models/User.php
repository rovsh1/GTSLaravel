<?php

namespace App\Site\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Sdk\Shared\Enum\Client\User\RoleEnum;
use Sdk\Shared\Enum\Client\User\StatusEnum;
use Sdk\Shared\Enum\GenderEnum;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'client_id',
        'country_id',
        'presentation',
        'gender',
        'login',
        'password',
        'email',
        'phone',
        'post_id',
        'address',
        'note',
        'status',
        'role',
        'birthday',
        'image',
        'recovery_hash',
    ];

    protected $casts = [
        'role' => RoleEnum::class,
        'status' => StatusEnum::class,
        'gender' => GenderEnum::class,
    ];

    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function isActive(): bool
    {
        return (bool)$this->status;
    }

    public function getDisplayName(): string
    {
        return (string)$this->presentation;
    }

    public function __toString()
    {
        return $this->getDisplayName();
    }
}
