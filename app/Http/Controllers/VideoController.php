<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VideoAccess;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class VideoController extends Controller
{
    private $videoFolder = 'mi_video';

    // Mostrar formulario
    public function form()
    {
        return view('video.form');
    }

    // Validar contraseña
    public function validateAccess(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $access = VideoAccess::where('expires_at', '>', now())->first();

        if (!$access || !Hash::check($request->password, $access->password)) {
            return back()->with('error', 'Contraseña inválida o vencida');
        }

        // 🔐 Generar token temporal
        $token = hash_hmac('sha256', now()->timestamp, config('app.key'));

        session([
            'video_token' => $token,
            'video_expires' => now()->addMinutes(30)
        ]);

        $playlistUrl = route('video.stream', [
            'file' => 'mi_video/mi_video.m3u8',
            'token' => $token
        ]);

        session([
            'playlistUrl' => $playlistUrl
        ]);

        return redirect()->route('video.player');
    }

    public function stream(Request $request, $file)
    {
        // 🔐 Validar token
        if (!$request->has('token') ||
            session('video_token') !== $request->token ||
            now()->gt(session('video_expires'))
        ) {
            abort(403);
        }

        $basePath = storage_path('app/private/hls/');
        $fullPath = $basePath . $file;

        if (!file_exists($fullPath)) {
            abort(404);
        }

        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension === 'm3u8') {

            $content = file_get_contents($fullPath);
            $lines = explode("\n", $content);

            foreach ($lines as &$line) {

                $line = trim($line);

                if (str_starts_with($line, '#')) {

                    // Firmar key AES con token
                    if (str_contains($line, 'URI="')) {
                        $keyUrl = route('video.key', [
                            'token' => $request->token
                        ]);

                        $line = '#EXT-X-KEY:METHOD=AES-128,URI="' . $keyUrl . '"';
                    }

                    continue;
                }

                if (str_contains($line, '.ts')) {

                    $segmentUrl = route('video.stream', [
                        'file' => dirname($file) . '/' . $line,
                        'token' => $request->token
                    ]);

                    $line = $segmentUrl;
                }
            }

            return response(implode("\n", $lines), 200, [
                'Content-Type' => 'application/vnd.apple.mpegurl'
            ]);
        }

        return response()->file($fullPath, [
            'Content-Type' => 'video/mp2t'
        ]);
    }

    public function key(Request $request)
    {
        if (!$request->has('token') ||
            session('video_token') !== $request->token ||
            now()->gt(session('video_expires'))
        ) {
            abort(403);
        }

        $path = storage_path('app/private/hls/mi_video/enc.key');

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/octet-stream'
        ]);
    }
}
