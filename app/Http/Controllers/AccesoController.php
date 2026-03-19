<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccesoController extends Controller
{
    public function index() {
        $accesos = DB::table('video_accesses')
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('accesos.index', compact('accesos'));
    }

    public function store(Request $request){
        // Validación básica
        $request->validate([
            'numero_accesos' => 'required|integer|min:1',
            'fecha_expiracion' => 'required|date'
        ]);

        $numeroAccesos = $request->numero_accesos;
        $fechaExpiracion = $request->fecha_expiracion;
        $data = [];

        for ($i = 0; $i < $numeroAccesos; $i++) {
            // Longitud aleatoria entre 6 y 8
            $length = rand(6, 8);

            // Generar contraseña con letras, números y símbolos
            $passwordPlano = $this->generatePassword($length);

            $data[] = [
                'role' => 'user',
                'password' => Hash::make($passwordPlano),
                'view_password' => $passwordPlano,
                'expires_at' => Carbon::parse($fechaExpiracion),
                'video_path' => 'videos/presentacion.mp4',
                'used' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Inserción masiva
        DB::table('video_accesses')->insert($data);

        return redirect()->back()->with('success', 'Accesos generados correctamente');
    }

    private function generatePassword($length = 8){
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=';
        $charactersLength = strlen($characters);
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }
}
