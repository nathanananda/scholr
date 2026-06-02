<?php

namespace App\Http\Middleware;

use App\Models\PenyalurProfile;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PenyalurVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $profile = PenyalurProfile::where('user_id', Auth::id())->first();

        // Belum ada profil sama sekali
        if (!$profile) {
            return redirect()->route('penyalur.profile')
                ->with('info', 'Lengkapi profil organisasi kamu terlebih dahulu.');
        }

        // Profil ada tapi belum verified
        if ($profile->verification_status !== 'verified') {
            $messages = [
                'pending'  => 'Profil kamu sedang menunggu verifikasi admin.',
                'rejected' => 'Profil kamu ditolak. Perbaiki data dan ajukan ulang.',
            ];

            $message = $messages[$profile->verification_status] ?? 'Lengkapi profil kamu terlebih dahulu.';

            return redirect()->route('penyalur.profile')
                ->with('error', $message);
        }

        return $next($request);
    }
}
