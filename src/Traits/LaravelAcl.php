<?php

namespace Palano\LaravelAcl\Traits;

use Palano\LaravelAcl\Models\Role;
use Illuminate\Support\Facades\Cache;

trait LaravelAcl
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_users');
    }

    /**
     * @return array
     **/
    public function permissions()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $roleKey = $role->getCacheKey();
            if (Cache::has($roleKey) === false) {
                $cachePermissions = $role->permissions()->pluck('slug')->toArray();
                Cache::forever($roleKey, $cachePermissions);
            }
            $permissions = array_merge($permissions, Cache::get($roleKey));
        }
        return $permissions;
    }

    /**
     * @param object|array|int $role
     *
     * @return void
     */
    public function attachRole($role)
    {
        dd($role->toArray());
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
     * @return bool
     **/
    public function hasRole($name)
    {
        $roles = $this->roles()->pluck('slug')->toArray();

        if (is_array($name)) {
            foreach ($name as $role) {
                if (in_array($role, $roles)) {
                    return true;
                }
            }
        } else {
            return in_array($name, $roles);
        }

        return false;
    }

    /**
     * @return bool
     **/
    public function hasPermission($name)
    {
        $permissions = array_unique($this->permissions());

        if (is_array($name)) {
            foreach ($name as $permission) {
                if (in_array($permission, $permissions)) {
                    return true;
                }
            }
        } else {
            return in_array($name, $permissions);
        }

        return false;
    }

    public function hasAbility($roles, $permissions)
    {
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }

        if (!is_array($permissions)) {
            $permissions = explode(',', $permissions);
        }

        $checkRoles = [];
        $checkPermissions = [];

        foreach ($roles as $role) {
            $checkRoles[$role] = $this->hasRole($role);
        }

        foreach ($permissions as $permission) {
            $checkPermissions[$permission] = $this->hasPermission($permission);
        }

        if (in_array(true, $checkRoles) || in_array(true, $checkPermissions)) {
            return true;
        }

        return false;
    }
}
