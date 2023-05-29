<?php

namespace Module\Hotel\Infrastructure\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sdk\Module\Database\Eloquent\Model;

class Season extends Model
{
    protected $table = 'hotel_seasons';

    protected $fillable = [
        'contract_id',
        'name',
        'date_start',
        'date_end',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    public function scopeWhereRoomId(Builder $builder, int $roomId)
    {
        //@todo пока убрал, нужно будет для Traveline
//        $builder->addSelect("{$this->getTable()}.*");
//
//        $joinableTable = with(new Room)->getTable();
//        $builder->leftJoin(
//            $joinableTable,
//            function (JoinClause $join) use ($joinableTable) {
//                $join->on("{$joinableTable}.hotel_id", '=', "{$this->getTable()}.hotel_id");
//            }
//        )->addSelect("{$joinableTable}.id as room_id");
//
//        $builder->where("{$joinableTable}.id", $roomId);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }
}
