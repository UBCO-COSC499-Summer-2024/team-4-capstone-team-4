#!/bin/bash

IS_AUDIT_LOG_ENABLED=${IS_AUDIT_LOG_ENABLED:-false}
echo "IS_AUDIT_LOG_ENABLED=$IS_AUDIT_LOG_ENABLED"

PGAUDIT_LOG_ARGS=""
if [ "${IS_AUDIT_LOG_ENABLED}" = "true" ] ; then

  # --- Use psql to configure pgaudit dynamically ---

  # Connect to PostgreSQL 
  psql -U postgres -d Insight <<EOF

  # Create the pgaudit extension if it doesn't exist
  CREATE EXTENSION IF NOT EXISTS pgaudit;

  # Set pgaudit configuration using environment variables
  ALTER SYSTEM SET pgaudit.log_relation = '${PGAUDIT_LOG_RELATION:-super_audits}';
  ALTER SYSTEM SET pgaudit.role = '${PGAUDIT_ROLES:-*}';
  ALTER SYSTEM SET pgaudit.log = '${PGAUDIT_ACTIONS:-all}';

  # Additional settings from your original script (if needed):
  ALTER SYSTEM SET pgaudit.log_level = '${PGAUDIT_LOG_LEVEL:-LOG}';
  ALTER SYSTEM SET pgaudit.log_catalog = '${PGAUDIT_LOG_CATALOG:-on}';
  ALTER SYSTEM SET pgaudit.log_parameter = '${PGAUDIT_LOG_PARAMETER:-on}';

EOF

fi

# --- Start PostgreSQL Server ---
echo "Starting PostgreSQL server..."
exec docker-entrypoint.sh "$@" $PGAUDIT_LOG_ARGS