<?php

namespace App\Http\Controllers;

use App\Exports\SvcroleExport;
use App\Models\ServiceRole;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServiceRoleController extends Controller
{
    // Method to export in various formats
    public function export($id, $format)
    {
        // dd($id,$format);
        // $id = $request->input('eid');
        // dd($id);
        // $format = $request->input('eformat');
        $serviceRole = ServiceRole::find($id);

        if (!$serviceRole) {
            return redirect()->route('svcroles.manage.id', $id)
                ->with('toast', ['message' => 'Service role not found.', 'type' => 'error']);
        }

        $validFormats = ['xlsx', 'csv', 'ods', 'pdf'];

        if (!in_array($format, $validFormats)) {
            return response()->json(['error' => 'Format not supported.'], 400);
        }

        if ($format === 'xlsx' || $format === 'csv' || $format === 'ods') {
            return Excel::download(new SvcroleExport($serviceRole), 'service_role_' . $serviceRole->name . '.' . $format);
        } elseif ($format === 'pdf') {
            return $this->exportPDF($id);
        } else {
            return response()->json(['error' => 'Format not supported.'], 400);
        }
    }

    // Method to export as PDF specifically
    public function exportPDF($id) {
        $serviceRoleId = $id;
        $serviceRole = ServiceRole::find($serviceRoleId);

        if (!$serviceRole) {
            return redirect()->back()->with('error', 'Service role not found.');
        }

        $svcExp = new SvcroleExport($serviceRole);
        return $svcExp->generatePDF();
    }

    public function exportAllPDF() {
        $serviceRoles = ServiceRole::all();

        if (!$serviceRoles) {
            return redirect()->back()->with('error', 'Service roles not found.');
        }

        $svcExp = new SvcroleExport($serviceRoles);
        return $svcExp->generateMultiplePDF();
    }

    public function exportMultiplePDF($ids) {
        $serviceRoleIds = $ids;
        $serviceRoles = ServiceRole::find($serviceRoleIds);

        if (!$serviceRoles) {
            return redirect()->back()->with('error', 'Service roles not found.');
        }

        $svcExp = new SvcroleExport($serviceRoles);
        return $svcExp->generateMultiplePDF();
    }

    public function index(Request $request) {
        $perPage = $request->input('per_page', 20);
        $page = $request->input('page', 1);
        $serviceRoles = ServiceRole::paginate($perPage, ['*'], 'page', $page);

        return response()->json($serviceRoles);
    }

    public function goToDash() {
        return redirect()->route('dasboard');
    }
}
