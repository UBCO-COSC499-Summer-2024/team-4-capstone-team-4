<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class AccessController extends Controller
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
            'upload' => 'UPLOAD SEI', // if they can upload SEI otherwise use UI entry
        ],
        'audit_log' => [
            'read' => 'READ AUDIT LOG',
            'delete' => 'DELETE AUDIT LOG',
        ],
        'request' => [
            'read' => 'READ REQUEST',
            'update' => 'UPDATE REQUEST', // approve, deny, cancel
            'delete' => 'DELETE REQUEST',
            'self.approve' => 'APPROVE OWN REQUEST',
        ]
    ];
    protected const ROLE_PERMISSIONS = [
        'admin' => [
            'CREATE SVCROLES', 'READ SVCROLES', 'UPDATE SVCROLES', 'DELETE SVCROLES',
            'ASSIGN SVCROLE INSTRUCTOR', 'REMOVE SVCROLE INSTRUCTOR', 'READ SVCROLE INSTRUCTOR',
            'SVCROLE ADD EXTRA HOURS', 'SVCROLE VIEW EXTRA HOURS', 'SVCROLE DELETE EXTRA HOURS',
            'CREATE STAFF', 'READ STAFF', 'UPDATE STAFF', 'DELETE STAFF',
            'CREATE COURSES', 'READ COURSES', 'UPDATE COURSES', 'DELETE COURSES',
            'CREATE SEI', 'READ SEI', 'UPDATE SEI', 'DELETE SEI', 'UPLOAD SEI',
            'READ AUDIT LOG', 'DELETE AUDIT LOG',
            'READ REQUEST', 'UPDATE REQUEST', 'DELETE REQUEST', 'APPROVE OWN REQUEST',
        ],
        'dept_head' => [
            'READ SVCROLES', 'ASSIGN SVCROLE INSTRUCTOR', 'REMOVE SVCROLE INSTRUCTOR',
            'SVCROLE ADD EXTRA HOURS', 'SVCROLE VIEW EXTRA HOURS', 'SVCROLE DELETE EXTRA HOURS',
            'READ STAFF', 'UPDATE STAFF',
            'CREATE COURSES', 'READ COURSES', 'UPDATE COURSES', 'DELETE COURSES',
            'READ SEI', 'UPDATE SEI', 'UPLOAD SEI',
            'READ AUDIT LOG',
            'READ REQUEST', 'UPDATE REQUEST', 'DELETE REQUEST',
        ],
        'dept_staff' => [
            'READ SVCROLES', 'UPDATE SVCROLES',
            'READ STAFF', 'UPDATE STAFF',
            'READ COURSES', 'UPDATE COURSES',
            'READ SEI', 'UPDATE SEI',
            'READ AUDIT LOG',
            'READ REQUEST', 'UPDATE REQUEST',
        ],
        'instructor' => [
            'READ SVCROLE INSTRUCTOR',
            'SVCROLE VIEW EXTRA HOURS',
            'READ STAFF',
            'READ COURSES',
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
        $this->routes = Route::getRoutes();
        $this->initializeRoutesAccess();
    }

    public static function check() {
        // when accessing a route, if it requires an id, check the user's id and if it has read.all/read.others/read.single or only read.self
        $access = new self();
        $user = $access->user;
        if (!$user) {
            return false;
        }
        // get current route
        $route = Route::current();
        $route_name = $route->getName();
        $route_parameters = $route->parameters();
    }

    public function getPermissionForRoute($routeName, $params) {
        $routePermissions = [
            'svcroles.manage.id' => 'read.all',
            'svcroles' => AccessController::PERMISSIONS['svcroles'],
            'svcroles.create' => 'create',
            // rest
        ];

        if (isset($routePermissions[$routeName])) {
            return $routePermissions[$routeName];
        }
    }

    public function initializeRoutesAccess()
    {
        $this->routes_access = [];

        // routes aren't necessarily named after permissions but some pages are role access controlled
        // instructor has access to public routes
        $this->routes_access[self::ROLES['instructor']] = $this->public_routes;
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
        $access = new AccessController();
        $access->initializeRoutesAccess();
        $access->routes_access[$role] = $access->public_routes;
        return $access;
    }

    public static function showIf($role, $item) {
        return in_array($role, auth()->user()->roles) ? $item : null;
    }

    /**
     * Show item if user has permission
     *
     * @param string $permission
     * @param mixed $item
     * @return mixed
     */
    public static function showIfPermission($permission, $item) {
        $access = new self();
        if ($access->can($permission)) {
            return $item;
        }
        return null;
    }

    public static function can($permission) {
        $user = self::$user;
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
