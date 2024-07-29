<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller {
    public function index($page = 1, $perpage = 20, $viewMode = 'table') {
        return view('audits', [
            'page' => $page,
            'perpage' => $perpage,
            'viewMode' => $viewMode,
        ]);
    }
}
