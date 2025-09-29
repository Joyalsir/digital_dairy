@echo off
echo Starting PHP syntax check for all files...
for /r %%f in (*.php) do (
    echo Checking %%f
    php -l "%%f" 2>&1 | findstr /v "No syntax errors detected" >> php_lint_errors.txt
)
echo PHP syntax check completed. Check php_lint_errors.txt for any errors.
