<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

class CashierController extends Controller
{
    // Halaman kasir: cari member lewat HP atau kode
    public function index(Request $r)
    {
        $member = null;
        $q = trim($r->query('q', ''));

        // Isi QR = link kartu (.../kartu/{token}); ambil token-nya saja
        if (str_contains($q, '/kartu/')) {
            $q = Str::before(Str::afterLast($q, '/kartu/'), '?');
            $q = trim($q, '/ ');
        }

        if ($q !== '') {
            $member = Member::where('member_code', $q)
                ->orWhere('phone', $q)
                ->orWhere('card_token', $q)
                ->first();

            if ($member) {
                $member->load(['vouchers' => fn ($v) => $v
                    ->where('status', 'available')
                    ->whereDate('expires_at', '>=', now())
                    ->with('voucher')]);
            } else {
                session()->now('notfound', 'Member tidak ditemukan.');
            }
        }

        return view('kasir.index', compact('member', 'q'));
    }

    // Simpan transaksi
    public function checkout(Request $r, MembershipService $svc)
    {
        $data = $r->validate([
            'member_id'         => 'required|exists:members,id',
            'subtotal'          => 'required|integer|min:1',
            'member_voucher_id' => 'nullable|integer',
        ]);

        $member = Member::findOrFail($data['member_id']);

        try {
            $trx = $svc->checkout($member, (int) $data['subtotal'], $data['member_voucher_id'] ?? null);
        } catch (RuntimeException $e) {
            return back()->withErrors(['subtotal' => $e->getMessage()])->withInput();
        }

        return redirect()->route('kasir.index', ['q' => $member->phone])
            ->with('success', "Transaksi {$trx->invoice_no} berhasil. Total Rp" . number_format($trx->total, 0, ',', '.') . ", poin didapat: {$trx->points_earned}.");
    }

    // Form pendaftaran member
    public function create()
    {
        return view('kasir.register');
    }

    // Daftarkan member, lalu langsung buka WhatsApp ke nomor member
    // dengan pesan + link kartu member yang sudah terisi
    public function store(Request $r, MembershipService $svc)
    {
        $data = $r->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|string|max:20|unique:members,phone',
            'email'      => 'nullable|email',
            'birth_date' => 'nullable|date',
        ]);

        $member = $svc->register($data);

        return redirect()->away($member->whatsappUrl());
    }
}
