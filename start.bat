@echo off
chcp 65001 >nul
cd /d "%~dp0"

set "PHP_EXE="
if exist "C:\php\php.exe" set "PHP_EXE=C:\php\php.exe"
if not defined PHP_EXE where php >nul 2>&1 && set "PHP_EXE=php"
if not defined PHP_EXE if exist "C:\xampp\php\php.exe" set "PHP_EXE=C:\xampp\php\php.exe"
if not defined PHP_EXE if exist "C:\OpenServer\modules\php\PHP-8.3\php.exe" set "PHP_EXE=C:\OpenServer\modules\php\PHP-8.3\php.exe"
if not defined PHP_EXE if exist "C:\OpenServer\modules\php\PHP-8.2\php.exe" set "PHP_EXE=C:\OpenServer\modules\php\PHP-8.2\php.exe"

if not defined PHP_EXE (
    echo.
    echo  [X] PHP не найден. Ожидается C:\php\php.exe или в PATH.
    echo      Проверь, что в папке C:\php есть файл php.exe
    echo.
    pause
    exit /b 1
)

echo.
echo  Запуск: %PHP_EXE%
echo  Сайт:   http://localhost:8000
echo  Стоп:   закрой окно или Ctrl+C
echo.

"%PHP_EXE%" -S localhost:8000 -t "%~dp0"

echo.
echo  Сервер остановлен или ошибка.
pause
