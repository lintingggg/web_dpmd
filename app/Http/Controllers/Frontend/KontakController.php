<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\HubungiKamiMail;
use App\Models\PengaturanKontak;

class KontakController extends Controller
{
    public function kirim(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'nullable|string|max:255',
            'pesan' => 'required|string|max:5000',
            'g-recaptcha-response' => 'required|string',
        ], [
            'g-recaptcha-response.required' => 'Mohon selesaikan verifikasi reCAPTCHA.',
        ]);

        // Verifikasi reCAPTCHA ke Google API
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('NOCAPTCHA_SECRET') ?? config('services.recaptcha.secret'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success')) {
            return back()->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal, silakan coba lagi.']);
        }

        // Ambil target email dari database PengaturanKontak
        $kontak = PengaturanKontak::first();
        $targetEmail = $kontak->email ?? 'dpmd@bangkalankab.go.id';

        // Kirim email
        try {
            Mail::to($targetEmail)->send(new HubungiKamiMail(
                $request->nama,
                $request->email,
                $request->subjek,
                $request->pesan
            ));
        } catch (\Exception $e) {
            return back()->withErrors(['pesan' => 'Gagal mengirim email. Silakan coba beberapa saat lagi.']);
        }

        return back()->with('success', 'Pesan Anda berhasil dikirim!');
    }
}
