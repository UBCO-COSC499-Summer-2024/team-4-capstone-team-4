<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller {
    public function index(Request $request) {
        return view('audits');
    }
}
