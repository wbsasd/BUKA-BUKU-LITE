<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(): View
    {
        return view('admin.memberships.index', ['memberships' => []]);
    }

    public function create(): View
    {
        return view('admin.memberships.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // No-op karena belum ada implementasi persistence.
        return redirect()->route('admin.memberships.index')->with('success', 'Membership created');
    }

    public function edit($membership = null): View
    {
        return view('admin.memberships.edit', ['membership' => $membership]);
    }

    public function update(Request $request, $membership = null): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return redirect()->route('admin.memberships.index')->with('success', 'Membership updated');
    }

    public function destroy($membership = null): RedirectResponse
    {
        return redirect()->route('admin.memberships.index')->with('success', 'Membership deleted');
    }
}

