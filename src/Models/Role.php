<?php

namespace Palano\LaravelAcl\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Palano\LaravelAcl\Models\Permission;
use Palano\LaravelAcl\Models\PermissionGroup;

class Role extends Model
{
    public $table = 'roles';

    protected $fillable = ['slug', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function permissionGroups()
    {
        return $this->belongsToMany(PermissionGroup::class, 'permission_groups_roles');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'), 'roles_users');
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return 'palano_acl_role_'.$this->slug;
    }

    public function deleteCache()
    {
        Cache::forget($this->getCacheKey());
    }

    /**
     * @param object|array|int $permission
     *
     * @return void
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            return $this->attachPermissions($permission);
        }

        $this->permissions()->attach($permission);
        $this->deleteCache();
    }

    /**
     * @param mixed $permissions
     *
     * @return void
     */
    public function attachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * @param object|array|int $permission
     *
     * @return void
     */
    public function detachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }

        if (is_array($permission)) {
            return $this->detachPermissions($permission);
        }

        $this->permissions()->detach($permission);
        $this->deleteCache();
    }

    /**
     * @param mixed $permissions
     *
     * @return void
     */
    public function detachPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * @param object|array|int $permissionGroup
     *
     * @return void
     */
    public function attachPermissionGroup($permissionGroup)
    {
        if (is_object($permissionGroup)) {
            $permissionGroup = $permissionGroup->getKey();
        }

        if (is_array($permissionGroup)) {
            return $this->attachPermissionGroups($permissionGroup);
        }

        $this->permissionGroups()->attach($permissionGroup);
    }

    /**
     * @param mixed $permissionGroups
     *
     * @return void
     */
    public function attachPermissionGroups($permissionGroups)
    {
        foreach ($permissionGroups as $permissionGroup) {
            $this->attachPermissionGroup($permissionGroup);
        }
    }

    /**
     * @param object|array|int $permissionGroup
     *
     * @return void
     */
    public function detachPermissionGroup($permissionGroup)
    {
        if (is_object($permissionGroup)) {
            $permissionGroup = $permissionGroup->getKey();
        }

        if (is_array($permissionGroup)) {
            return $this->detachPermissionGroups($permissionGroup);
        }

        $this->permissionGroups()->detach($permissionGroup);
    }

    /**
     * @param mixed $permissionGroups
     *
     * @return void
     */
    public function detachPermissionGroups($permissionGroups)
    {
        foreach ($permissionGroups as $permissionGroup) {
            $this->detachPermissionGroup($permissionGroup);
        }
    }
}
