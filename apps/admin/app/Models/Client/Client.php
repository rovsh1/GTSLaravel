<?php

namespace App\Admin\Models\Client;

use Custom\Framework\Database\Eloquent\HasQuicksearch;
use Custom\Framework\Database\Eloquent\Model;

class Client extends Model
{
    use HasQuicksearch;

    public $timestamps = false;

    protected array $quicksearch = ['id', 'name%'];

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'city_id',
        'currency_id',
        'type',
        'status',
        'description'
    ];

    public function __toString()
    {
        return (string)$this->name;
    }
}
