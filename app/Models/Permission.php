<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'type', 'role_id'];


    public static function getPermissionsByRoleId($roleId)
    {
        $permissions = self::all();

        return $permissions->filter(function ($perm) use ($roleId) {
            $roleIds = json_decode($perm->role_id, true);

            return is_array($roleIds) && in_array($roleId, $roleIds);
        });
    }


}
