<?php
namespace Palano\LaravelAcl\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Ability
{
    private $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $permissions
     * @param  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions)
    {
        if (!is_array($roles)) {
            $roles = explode('|', $roles);
        }

        if (!is_array($permissions)) {
            $permissions = explode('|', $permissions);
        }

        if ($this->auth->guest() || !$this->auth->user()->hasAbility($roles, $permissions)) {
            abort(403);
        }
        
        return $next($request);
    }
}
