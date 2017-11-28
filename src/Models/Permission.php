<?php

namespace Palano\LaravelAcl\Models;

use Palano\LaravelAcl\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Palano\LaravelAcl\Models\PermissionGroup;

class Permission extends Model
{
    public $table = 'permissions';

    protected $fillable = ['slug', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function permissionGroups()
    {
        return $this->belongsToMany(PermissionGroup::class, 'permission_groups_permissions');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }

    /**
     * @param object|array|int $role
     *
     * @return void
     */
    public function attachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            return $this->attachRoles($role);
        }

        $this->roles()->attach($role);
    }

    /**
     * @param mixed $roles
     *
     * @return void
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * @param object|array|int $role
     *
     * @return void
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            return $this->detachRoles($role);
        }

        $this->roles()->detach($role);
    }

    /**
     * @param mixed $roles
     *
     * @return void
     */
    public function detachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->detachRole($role);
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
