@foreach ($auditLogs as $log)
    <div class="audit-log-item list-item item">
        <h3>Log ID: {{ $log->log_id }}</h3>
        <p>Description: {{ $log->description }}</p>
        <p>Action: {{ $log->action }}</p>
        <p>Operation Type: {{ $log->operation_type }}</p>
        <p>User ID: {{ $log->user_alt }}</p>
        <p>User Alt: {{ $log->user_alt }}</p>
        <p>Table: {{ $log->table_name }}</p>
        <p>Old Data: {{ $log->old_data }}</p>
        <p>New Data: {{ $log->new_data }}</p>
        <p>Timestamp: {{ $log->timestamp }}</p>
    </div>
@endforeach
