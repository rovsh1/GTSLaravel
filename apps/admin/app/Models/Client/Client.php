<?php

namespace App\Admin\Models\Client;

use Sdk\Module\Database\Eloquent\HasQuicksearch;
use Sdk\Module\Database\Eloquent\Model;

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
