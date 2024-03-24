<?php

namespace App\Shared\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;
use Sdk\Shared\Enum\Client\User\RoleEnum;
use Sdk\Shared\Enum\Client\User\StatusEnum;
use Sdk\Shared\Enum\GenderEnum;

class User extends Model
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

    public function getDisplayName(): string
    {
        return (string)$this->presentation;
    }

    public function __toString()
    {
        return $this->getDisplayName();
    }
}
