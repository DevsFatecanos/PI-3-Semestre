@echo off
REM Script para gerar APP_KEY no Windows

echo ===================================
echo Gerador de APP_KEY para Render
echo ===================================
echo.

REM Verificar se está em um projeto Laravel
if not exist "artisan" (
    echo Erro: arquivo 'artisan' nao encontrado.
    echo Execute este script na raiz do projeto Laravel.
    exit /b 1
)

REM Gerar APP_KEY
echo Gerando APP_KEY...
php artisan key:generate --force

REM Ler o APP_KEY do .env
for /f "tokens=2 delims==" %%A in ('findstr /R "^APP_KEY=" .env') do set APP_KEY=%%A

echo.
echo APP_KEY gerada com sucesso!
echo.
echo Copie este valor e cole no Render em:
echo Environment Variables ^> APP_KEY
echo.
echo ================================
echo %APP_KEY%
echo ================================
echo.
echo Pressione qualquer tecla...
pause
