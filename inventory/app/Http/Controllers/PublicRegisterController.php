<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\MembershipService;
use Illuminate\Http\Request;

class PublicRegisterController extends Controller
{
    // Halaman depan untuk pembeli: daftar member sendiri
    public function create()
    {
        return view('member.daftar');
    }

    public function store(Request $r, MembershipService $svc)
    {
        // Honeypot: kolom ini tersembunyi, bot biasanya mengisinya
        if ($r->filled('website')) {
            return redirect()->route('home');
        }

        // Rapikan nomor HP: buang spasi/simbol, awalan +62 / 62 jadi 0
        $phone = preg_replace('/\D/', '', (string) $r->input('phone'));
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }
        $r->merge(['phone' => $phone]);

        $data = $r->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|digits_between:9,15',
            'email'      => 'nullable|email|max:100',
            'birth_date' => 'nullable|date|before:today',
        ], [
            'phone.digits_between' => 'Nomor HP tidak valid.',
        ]);

        // Nomor sudah terdaftar: jangan bocorkan kartu orang lain
        if (Member::where('phone', $data['phone'])->exists()) {
            return back()->withInput()->withErrors([
                'phone' => 'Nomor ini sudah terdaftar. Minta link kartu member ke kasir ya.',
            ]);
        }

        $member = $svc->register($data);

        return redirect()->route('member.card', $member->card_token)
            ->with('success', "Selamat datang, {$member->name}! Voucher member baru sudah masuk. Simpan halaman ini sebagai kartu member kamu.");
    }
}
