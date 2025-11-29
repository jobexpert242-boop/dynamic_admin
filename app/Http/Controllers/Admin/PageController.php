<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PageController extends Controller
{
    use AuthorizesRequests;
    
    public function grapesjs(Request $request)
    {
        $this->authorize('grapjs.show');

        $query = Page::query();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        return Inertia::render('Admin/GrapesEditor/Index', [
            'pages' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    public function home()
    {
        return Inertia::render('Admin/GrapesEditor/Editor', [
            'page' => [
                'id' => null,
                'title' => 'New Page',
                'slug' => 'new-page',
                'content' => ['blocks' => []],
            ]
        ]);
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return Inertia::render('Admin/GrapesEditor/Editor', ['page' => $page]);
    }

    // Save title + html + json snapshot
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string',
            'html'  => 'nullable|string',
            'json'  => 'nullable|array',
        ]);

        $page->update([
            'title' => $data['title'],
            'html'  => $data['html'] ?? $page->html,
            'json'  => $data['json'] ?? $page->json,
        ]);

        return response()->json(['ok' => true, 'page' => $page]);
    }

    // Create a new page 
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'slug'  => 'nullable|string',
        ]);

        $slug = $data['slug'] ?? Str::slug($data['title']);
        $page = Page::create(['title' => $data['title'], 'slug' => $slug, 'json' => [], 'html' => '']);
        return redirect()->route('pages.edit', $page->id);
    }

    // Upload image from GrapesJS asset uploader
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->store('public/uploads');
        $url = Storage::url($path);

        return response()->json(['success' => true, 'url' => $url]);
    }

    // Frontend render by slug
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return Inertia::render('Admin/GrapesEditor/RenderPage', ['page' => $page]);
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();
        return redirect()->back()->with('status', 'Grapesjs Page deleted!');
    }
}
