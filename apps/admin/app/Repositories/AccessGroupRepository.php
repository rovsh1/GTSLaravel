<?php

namespace App\Admin\Repositories;

use App\Admin\Components\Factory\Support\DefaultRepository;
use App\Admin\Models\Administrator\AccessGroup;
use App\Admin\Models\Administrator\AccessRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccessGroupRepository extends DefaultRepository
{
    public function __construct()
    {
        parent::__construct(AccessGroup::class);
    }

    public function create(array $data): Model
    {
        $model = parent::create($data);

        $this->updateGroupPermissions($model->id, $data['rules'] ?? []);

        return $model;
    }

    public function update(int $id, array $data): bool
    {
        if (!parent::update($id, $data)) {
            return false;
        }

        $this->updateGroupPermissions($id, $data['rules'] ?? []);

        return true;
    }

    public function getGroupPermissions(int $groupId): Collection
    {
        return AccessRule::where('group_id', $groupId)->get();
    }

    public function updateGroupPermissions(int $groupId, array $rules): void
    {
        AccessRule::where('group_id', $groupId)
            ->delete();

        $insert = [];
        foreach ($rules as $resource => $permissions) {
            foreach ($permissions as $permission => $flag) {
                if ($flag == 1) {
                    $insert[] = [
                        'group_id' => $groupId,
                        'resource' => $resource,
                        'permission' => $permission,
                        'flag' => true,
                    ];
                }
            }
        }

        if ($insert) {
            DB::table('administrator_access_rules')->insert($insert);
        }
    }
}
