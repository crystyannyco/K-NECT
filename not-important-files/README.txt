================================================================================
NOT IMPORTANT FILES - MOVED ON October 22, 2025
================================================================================

This folder contains files that were moved from the K-NECT root directory.
These files are NOT required for the system to function and can be safely
removed or archived.

--------------------------------------------------------------------------------
FILES MOVED:
--------------------------------------------------------------------------------

1. verify_csrf_config.php
   - Debug/verification script for CSRF configuration
   - Used only for testing CSRF settings
   - Safe to delete

2. check_barangay.php
   - Database structure verification script
   - Used for checking barangay table structure
   - Safe to delete

3. run_migration.php
   - Database migration runner script
   - Used during initial setup/migration
   - Safe to delete (migrations already completed)

4. continue_migration.php
   - Migration continuation script
   - Used during database schema updates
   - Safe to delete (migrations already completed)

5. security-audit.bat
   - Security audit script for finding potential issues
   - Used during development/pre-deployment
   - Safe to delete (or keep for future audits)

6. restart-and-fix.bat
   - Quick restart utility script
   - Development helper tool
   - Safe to delete

7. README.md
   - Project documentation file
   - Contains setup instructions
   - Safe to delete (or keep for reference)

8. LICENSE
   - MIT License file
   - Safe to delete (or keep for legal reference)

9. phpunit.xml.dist
   - PHPUnit testing configuration
   - Used for running automated tests
   - Safe to delete (no tests currently in project)

10. DATABASE/ (folder)
    - Contains SQL dump files and migration scripts
    - Used for database setup and backups
    - Safe to archive/move to backup location

11. build/ (folder)
    - Contains build logs
    - Development/build artifacts
    - Safe to delete

12. builds (file)
    - Build-related file
    - Safe to delete

13. setup.bat
    - Windows installation/setup script
    - Only needed for initial installation
    - Safe to delete (setup already completed)

14. setup.sh
    - Linux/Mac installation/setup script
    - Only needed for initial installation
    - Safe to delete (setup already completed)

15. setup_scheduled_events.php
    - Cron job setup script
    - One-time configuration for scheduled tasks
    - Safe to delete (cron already configured)

16. setup_scheduled_events.sh
    - Shell script for cron setup
    - One-time configuration
    - Safe to delete (cron already configured)

17. preload.php
    - PHP opcache preload configuration
    - Rarely used optimization feature
    - Safe to delete

18. .env.example
    - Environment configuration template
    - Only needed for new installations
    - Safe to delete (.env already configured)

19. tailwind-build.bat
    - Tailwind CSS build script
    - Safe to delete (CSS already compiled)

20. tailwind-dev.bat
    - Tailwind CSS development watch script
    - Safe to delete (CSS already compiled)

--------------------------------------------------------------------------------
IMPORTANT: ESSENTIAL FILES KEPT IN ROOT
--------------------------------------------------------------------------------

The following files remain in the root directory as they are REQUIRED for
the system to function:

✓ .env - Environment configuration (CRITICAL)
✓ .htaccess - Web server configuration (CRITICAL)
✓ composer.json - PHP dependencies (CRITICAL)
✓ package.json - Node.js dependencies (CRITICAL)
✓ tailwind.config.js - Tailwind CSS configuration (CRITICAL)
✓ spark - CodeIgniter CLI tool (CRITICAL)
✓ cron_publish_events.php/sh - Active cron job (CRITICAL)

✓ app/ - Application code (CRITICAL)
✓ public/ - Public web files (CRITICAL)
✓ vendor/ - PHP dependencies (CRITICAL)
✓ writable/ - Logs, cache, uploads (CRITICAL)
✓ node_modules/ - Node.js dependencies (CRITICAL)
✓ .git/ - Git repository (CRITICAL)
✓ .gitignore - Git configuration (CRITICAL)
✓ composer.lock - Dependency lock file (CRITICAL)
✓ package-lock.json - NPM lock file (CRITICAL)

--------------------------------------------------------------------------------
RECOMMENDATION:
--------------------------------------------------------------------------------

If you want to completely clean up the project:
1. You can DELETE this entire "not-important-files" folder
2. Or keep it as an archive for reference
3. Consider backing up DATABASE/ folder elsewhere before deletion

The K-NECT system will continue to work perfectly without any of these files.

================================================================================
