<?php
// Script para verificar e criar usuário admin

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Verificar se admin existe
$admin = User::where('email', 'admin@loja.com')->first();

if ($admin) {
    echo "✓ Admin existe: {$admin->name} (ID: {$admin->id})\n";
    if (!$admin->is_admin) {
        $admin->update(['is_admin' => true]);
        echo "✓ Admin foi marcado como is_admin = true\n";
    } else {
        echo "✓ Admin já tem is_admin = true\n";
    }
} else {
    echo "✗ Admin não encontrado. Criando...\n";
    $newAdmin = User::create([
        'name' => 'Administrador',
        'email' => 'admin@loja.com',
        'password' => Hash::make('admin123'),
        'is_admin' => true,
    ]);
    echo "✓ Admin criado com sucesso (ID: {$newAdmin->id})\n";
}

echo "\nCredenciais:\n";
echo "Email: admin@loja.com\n";
echo "Senha: admin123\n";
