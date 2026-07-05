<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.settings.index', ['settings' => []]);
    }

    public function create(): View
    {
        return view('admin.settings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Placeholder, karena belum ada model/table setting di repo.
        $request->validate([
            'key' => ['required', 'string'],
            'value' => ['nullable', 'string'],
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Setting saved');
    }

    public function edit($setting = null): View
    {
        return view('admin.settings.edit', ['setting' => $setting]);
    }

    public function update(Request $request, $setting = null): RedirectResponse
    {
        $request->validate([
            'key' => ['required', 'string'],
            'value' => ['nullable', 'string'],
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Setting updated');
    }

    public function destroy($setting = null): RedirectResponse
    {
        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted');
    }
}

