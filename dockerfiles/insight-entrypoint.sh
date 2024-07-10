#!/bin/bash

<<<<<<< HEAD
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
=======
# Load environment variables from .env file
set -a
. /var/lib/postgresql/.env
set +a

IS_AUDIT_LOG_ENABLED=${IS_AUDIT_LOG_ENABLED:-false}
echo "IS_AUDIT_LOG_ENABLED=$IS_AUDIT_LOG_ENABLED"

if [ "${IS_AUDIT_LOG_ENABLED}" = "true" ] ; then
  # Connect to PostgreSQL
  psql -U postgres -d Insight <<EOF
CREATE EXTENSION IF NOT EXISTS pgaudit;
ALTER SYSTEM SET pgaudit.log_relation = '${PGAUDIT_LOG_RELATION}';
ALTER SYSTEM SET pgaudit.role = '${PGAUDIT_ROLES}';
ALTER SYSTEM SET pgaudit.log = '${PGAUDIT_ACTIONS}';
ALTER SYSTEM SET pgaudit.log_level = '${PGAUDIT_LOG_LEVEL}';
ALTER SYSTEM SET pgaudit.log_catalog = '${PGAUDIT_LOG_CATALOG}';
ALTER SYSTEM SET pgaudit.log_parameter = '${PGAUDIT_LOG_PARAMETER}';
ALTER SYSTEM SET pgaudit.log_statement = '${PGAUDIT_LOG_STATEMENT}';
ALTER SYSTEM SET pgaudit.log_client = '${PGAUDIT_LOG_CLIENT}';
ALTER SYSTEM SET pgaudit.log_source = '${PGAUDIT_LOG_SOURCE}';
ALTER SYSTEM SET pgaudit.log_session = '${PGAUDIT_LOG_SESSION}';
ALTER SYSTEM SET pgaudit.log_user = '${PGAUDIT_LOG_USER}';
ALTER SYSTEM SET pgaudit.log_database = '${PGAUDIT_LOG_DATABASE}';
SELECT pg_reload_conf();
EOF
fi

echo "Starting PostgreSQL server..."
exec docker-entrypoint.sh "$@"
>>>>>>> origin/pre-dev-integration
