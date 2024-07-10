<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccessControl
{
    protected const ROLES = [
        'admin' => 'Admin',
        'dept_head' => 'Department Head',
        'dept_staff' => 'Department Staff',
        'instructor' => 'Instructor',
    ];

    protected const PERMISSIONS = [
        'svcroles' => [
            'create' => 'CREATE SVCROLES',
            'read.all' => 'READ ALL SVCROLES',
            'read.assigned' => 'READ ASSIGNED SVCROLE',
            'update' => 'UPDATE SVCROLES',
            'delete' => 'DELETE SVCROLES',
        ],
        'svcrole_instructor' => [
            'assign' => 'ASSIGN SVCROLE INSTRUCTOR',
            'remove' => 'REMOVE SVCROLE INSTRUCTOR',
            'read.all' => 'READ SVCROLE INSTRUCTORS',
        ],
        'extra_hours' => [
            'add' => 'SVCROLE ADD EXTRA HOURS',
            'read' => 'SVCROLE VIEW EXTRA HOURS',
            'delete' => 'SVCROLE DELETE EXTRA HOURS',
        ],
        'staff' => [
            'create' => 'CREATE STAFF',
            'read.all' => 'READ ALL STAFF',
            'read.self' => 'READ OWN STAFF',
            'update' => 'UPDATE STAFF',
            'delete' => 'DELETE STAFF',
        ],
        'courses' => [
            'create' => 'CREATE COURSES',
            'read' => 'READ COURSES',
            'read.assigned' => 'READ ASSIGNED COURSES',
            'update' => 'UPDATE COURSES',
            'delete' => 'DELETE COURSES',
        ],
        'sei' => [
            'create' => 'CREATE SEI',
            'read' => 'READ SEI',
            'update' => 'UPDATE SEI',
            'delete' => 'DELETE SEI',
            'upload' => 'UPLOAD SEI',
        ],
        'audit_log' => [
            'read' => 'READ AUDIT LOG',
            'delete' => 'DELETE AUDIT LOG',
        ],
        'request' => [
            'read' => 'READ REQUEST',
            'update' => 'UPDATE REQUEST',
            'delete' => 'DELETE REQUEST',
            'self.approve' => 'APPROVE OWN REQUEST',
        ]
    ];

    protected const ROLE_PERMISSIONS = [
        'admin' => [
            'CREATE SVCROLES', 'READ ALL SVCROLES', 'UPDATE SVCROLES', 'DELETE SVCROLES',
            'ASSIGN SVCROLE INSTRUCTOR', 'REMOVE SVCROLE INSTRUCTOR', 'READ ALL SVCROLE INSTRUCTORS',
            'SVCROLE ADD EXTRA HOURS', 'SVCROLE VIEW EXTRA HOURS', 'SVCROLE DELETE EXTRA HOURS',
            'CREATE STAFF', 'READ ALL STAFF', 'UPDATE STAFF', 'DELETE STAFF',
            'CREATE COURSES', 'READ COURSES', 'UPDATE COURSES', 'DELETE COURSES',
            'CREATE SEI', 'READ SEI', 'UPDATE SEI', 'DELETE SEI', 'UPLOAD SEI',
            'READ AUDIT LOG', 'DELETE AUDIT LOG',
            'READ REQUEST', 'UPDATE REQUEST', 'DELETE REQUEST', 'APPROVE OWN REQUEST',
        ],
        'dept_head' => [
            'READ ALL SVCROLES', 'ASSIGN SVCROLE INSTRUCTOR', 'REMOVE SVCROLE INSTRUCTOR',
            'SVCROLE ADD EXTRA HOURS', 'SVCROLE VIEW EXTRA HOURS', 'SVCROLE DELETE EXTRA HOURS',
            'READ ALL STAFF', 'UPDATE STAFF',
            'CREATE COURSES', 'READ COURSES', 'UPDATE COURSES', 'DELETE COURSES',
            'READ SEI', 'UPDATE SEI', 'UPLOAD SEI',
            'READ AUDIT LOG',
            'READ REQUEST', 'UPDATE REQUEST', 'DELETE REQUEST',
        ],
        'dept_staff' => [
            'READ ALL SVCROLES', 'UPDATE SVCROLES',
            'READ ALL STAFF', 'UPDATE STAFF',
            'READ COURSES', 'UPDATE COURSES',
            'READ SEI', 'UPDATE SEI',
            'READ AUDIT LOG',
            'READ REQUEST', 'UPDATE REQUEST',
        ],
        'instructor' => [
            'READ ASSIGNED SVCROLE',
            'SVCROLE VIEW EXTRA HOURS',
            'READ SELF STAFF',
            'READ ASSIGNED COURSES',
            'READ SEI',
            'READ AUDIT LOG',
            'READ REQUEST', 'APPROVE OWN REQUEST',
        ],
    ];

    protected $user;
    protected $routes;
    protected $routes_access;
    protected $public_routes = [
        'login', 'register', 'password.request', 'password.reset', 'verification.notice', 'verification.verify', 'verification.resend', 'auth.provider', 'auth.provider.callback', 'privacy-policy', 'tos', 'main', 'dashboard', 'notifications', 'help',
        'user.profile.show', 'user.profile.update', 'user.password.edit', 'user.password.update', 'user.api-tokens.index', 'user.api-tokens.create', 'user.api-tokens.store', 'user.api-tokens.edit', 'user.api-tokens.update', 'user.api-tokens.destroy',
    ];

    public function __construct()
    {
        $this->user = auth()->user();
        $this->routes = app('router')->getRoutes();
        $this->initializeRoutesAccess();
    }

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();

        if (in_array($routeName, $this->public_routes)) {
            return $next($request);
        }

        if (!$this->user || !$this->canAccessRoute($routeName)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }

    protected function initializeRoutesAccess()
    {
        $this->routes_access = [];

        foreach (self::ROLES as $role => $roleName) {
            $this->routes_access[$role] = $this->public_routes;
        }

        foreach (self::ROLE_PERMISSIONS as $role => $permissions) {
            foreach ($permissions as $permission) {
                foreach (self::PERMISSIONS as $resource => $actions) {
                    if (in_array($permission, $actions)) {
                        $this->routes_access[$role][] = "{$resource}.*";
                    }
                }
            }
        }
    }

    protected function canAccessRoute($routeName)
    {
        foreach ($this->user->roles as $role) {
            if (isset($this->routes_access[$role]) && in_array($routeName, $this->routes_access[$role])) {
                return true;
            }
        }

        return false;
    }

    public static function check()
    {
        $access = new self();
        $user = $access->user;
        if (!$user) {
            return false;
        }

        $route = Route::current();
        $routeName = $route->getName();
        $routeParameters = $route->parameters();

        return $access->canAccessRoute($routeName);
    }

    public static function getRoutesAccess()
    {
        $access = new self();
        return $access->routes_access;
    }

    public static function allPermissions()
    {
        $permissions = [];
        foreach (self::PERMISSIONS as $permission) {
            $permissions = array_merge($permissions, $permission);
        }
        return $permissions;
    }

    public static function useAsRole($role)
    {
        $access = new AccessControl();
        $access->initializeRoutesAccess();
        $access->routes_access[$role] = $access->public_routes;
        return $access;
    }

    public static function showIf($role, $item) {
        $user = auth()->user();
        return $user && in_array($role, $user->roles) ? $item : null;
    }

    public static function showIfPermission($permission, $item) {
        $access = new self();
        if ($access->can($permission)) {
            return $item;
        }
        return null;
    }

    public function can($permission) {
        $user = $this->user;
        if (!$user) {
            return false;
        }

        $roles = $user->roles;

        foreach ($roles as $role) {
            if (isset(self::ROLE_PERMISSIONS[$role])) {
                if (in_array($permission, self::ROLE_PERMISSIONS[$role])) {
                    return true;
                }
            }
        }

        return false;
    }
}
