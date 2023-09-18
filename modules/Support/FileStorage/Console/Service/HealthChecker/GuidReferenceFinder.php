<?php

namespace Module\Support\FileStorage\Console\Service\HealthChecker;

use Illuminate\Support\Facades\DB;
use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\GuidReferenceDto;

class GuidReferenceFinder
{
    private string $filesTable = 'files';

    private readonly array $referencedTables;

    public function __construct()
    {
        $this->referencedTables = $this->getReferencedTables($this->filesTable);
    }

    /**
     * @param string $guid
     * @return GuidReferenceDto[]
     */
    public function findUsage(string $guid): array
    {
        $usage = [];
        foreach ($this->referencedTables as $table) {
            $flag = DB::table($table->table)
                ->where($table->column, $guid)
                ->exists();
            if ($flag) {
                $usage[] = $table;
            }
        }

        return $usage;
    }

    private function getReferencedTables(string $table): array
    {
        $result = DB::select(
            'SELECT *'
            . ' FROM  information_schema.KEY_COLUMN_USAGE'
            . ' WHERE REFERENCED_TABLE_NAME="' . $table . '"'
            . ' AND REFERENCED_COLUMN_NAME = \'guid\''
        );

        return array_map(fn($r) => new GuidReferenceDto(
            $r->TABLE_NAME,
            $r->COLUMN_NAME,
        ), $result);
    }
}