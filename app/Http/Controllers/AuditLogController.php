<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::orderBy('timestamp', 'desc')->paginate(10);
        $viewMode = 'card';

        return view('audits', [
            'auditLogs' => $auditLogs,
            'viewMode' => $viewMode,
        ]);
    }
}
