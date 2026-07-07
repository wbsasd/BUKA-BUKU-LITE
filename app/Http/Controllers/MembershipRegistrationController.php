<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MembershipRegistrationController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create()
    {
        return view('membership.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:1000'],


            // Optional uploads (form may enable/disable)
            'ktp' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,pdf', 'max:10240'],
            'selfie' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,pdf', 'max:10240'],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Upload file if provided
        $ktpPath = null;
        if ($request->hasFile('ktp')) {
            $ktpPath = $request->file('ktp')->store('membership/ktp', 'public');
        }

        $selfiePath = null;
        if ($request->hasFile('selfie')) {
            $selfiePath = $request->file('selfie')->store('membership/selfie', 'public');
        }

        // Create user membership request.
        // Project saat ini hanya memiliki kolom users: name, email, password, role, membership_status.
        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pengguna',
            'membership_status' => 'pending',
        ]);

        // Jika model/future uses membutuhkan penyimpanan detail membership (ktp/selfie/phone/address/username),
        // saat ini belum ada tabel/kolom yang jelas di project ini, sehingga file disimpan hanya untuk kebutuhan dokumen.
        // (Tidak ada perubahan sistem login/approval admin.)

        return redirect()->route('login')->with('success', 'Permohonan membership berhasil diajukan. Status Anda: pending.');
    }
}


