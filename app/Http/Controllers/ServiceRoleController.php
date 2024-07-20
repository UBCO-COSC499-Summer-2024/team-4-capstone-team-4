<?php

namespace App\Http\Controllers;

use App\Exports\SvcroleExport;
use App\Models\ServiceRole;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServiceRoleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 20);
        $page = $request->input('page', 1);
        $serviceRoles = ServiceRole::paginate($perPage, ['*'], 'page', $page);

        return response()->json($serviceRoles);
    }

    public function export() {
        return Excel::download(new SvcroleExport, 'service_roles.xlsx');
    }
}
