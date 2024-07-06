<?php

namespace App\Http\Controllers;

use App\Models\ServiceRole;
use Illuminate\Http\Request;

class ServiceRoleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $page = $request->input('page', 1);
        $serviceRoles = ServiceRole::paginate($perPage, ['*'], 'page', $page);

        return response()->json($serviceRoles);
    }
}
