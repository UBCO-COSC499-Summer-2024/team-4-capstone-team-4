<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller {
    public function index(Request $request) {
        $filters = $this->populateFilters();
        return view('audits', ['filters' => $filters]);
    }

    private function populateFilters() {
        // Initialize an array to hold distinct values
        $filters = [];

        // Fetch distinct user_ids
        $userIds = AuditLog::select('user_id')
                           ->whereNotNull('user_id')
                           ->distinct()
                           ->get()
                           ->pluck('user_id');

        // Fetch distinct user_alt values
        $userAlts = AuditLog::select('user_alt')
                            ->whereNotNull('user_alt')
                            ->distinct()
                            ->get()
                            ->pluck('user_alt');

        // Combine user_ids and user_alts, remove duplicates
        $combinedUsers = $userIds->merge($userAlts)->unique()->sort();

        // Populate the filters array
        $filters['Users'] = $combinedUsers;
        $filters['Actions'] = AuditLog::select('action')->distinct()->get()->pluck('action');
        $filters['Schemas'] = AuditLog::select('table_name')->distinct()->get()->pluck('table_name');
        $filters['Operations'] = AuditLog::select('operation_type')->distinct()->get()->pluck('operation_type');

        return $filters;
    }
}
