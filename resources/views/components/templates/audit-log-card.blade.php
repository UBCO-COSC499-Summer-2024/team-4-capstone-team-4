@foreach ($auditLogs as $log)
    <div class="audit-log-item card item">
        <div class="card-header">
            <h3>Log ID: {{ $log->log_id }}</h3>
            <p>Table: {{ $log->table_name }}</p>
        </div>
        <div class="card-body">
            <p>Description: {{ $log->description }}</p>
            <p>Action: {{ $log->action }}</p>
            <p>Operation Type: {{ $log->operation_type }}</p>
            <p>User: {{ $log->user_alt }}</p>
            {{-- Additional fields as needed --}}
        </div>
        <div class="card-footer">
            <p>Timestamp: {{ $log->timestamp }}</p>
        </div>
    </div>
@endforeach
