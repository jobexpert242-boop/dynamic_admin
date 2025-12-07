<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SiteSettingController extends Controller
{
    use AuthorizesRequests;

    public function docs()
    {
        $this->authorize('docs.show');

        return Inertia::render('Admin/Docs/Docs', [
            'site' => SiteSetting::first(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'docs' => 'nullable|string',
        ]);

        $setting = SiteSetting::first();

        if (!$setting) {
            $setting = SiteSetting::create($data);
        } else {
            $setting->update($data);
        }

        return redirect()->back()->with('status', 'Documentation updated successfully');
    }

    public function siteSetting()
    {
        $this->authorize('show.sitesetting');

        return Inertia::render('Admin/SiteSetting/SiteSetting', [
            'site' => SiteSetting::first(),
        ]);
    }

    public function siteSettingUpdate(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'favaicon' => 'nullable|image|mimes:jpeg,png,jpg,ico,svg|max:1024',
            'inv_termes' => 'nullable',
            'tax' => 'nullable',
            'inv_prefix' => 'nullable',
        ]);

        $site = SiteSetting::first() ?? new SiteSetting();

        if ($request->hasFile('logo')) {
            if ($site->logo && Storage::disk('public')->exists($site->logo)) {
                Storage::disk('public')->delete($site->logo);
            }

            $site->logo = $request->file('logo')->store('site/logo', 'public');
        }

        if ($request->hasFile('favaicon')) {
            if ($site->favaicon && Storage::disk('public')->exists($site->favaicon)) {
                Storage::disk('public')->delete($site->favaicon);
            }

            $site->favaicon = $request->file('favaicon')->store('site/favaicon', 'public');
        }

        $site->inv_termes = $request->inv_termes;
        $site->tax = $request->tax;
        $site->inv_prefix = $request->inv_prefix;

        $site->save();

        return redirect()->back()->with('status', 'Site settings updated successfully.');
    }
}
