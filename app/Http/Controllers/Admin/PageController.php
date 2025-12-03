<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PageController extends Controller
{
    public function grapesjs(Request $request)
    {
        $query = Page::query();
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        $pages = $query->latest()->paginate(10)->withQueryString();
        return Inertia::render('Admin/GrapesEditor/Index', ['pages' => $pages]);
    }

    public function home()
    {
        return Inertia::render('Admin/GrapesEditor/Editor', [
            'page' => [
                'id' => null,
                'title' => '',
                'slug' => '',
                'html' => '',
                'json' => [],
            ],
        ]);
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return Inertia::render('Admin/GrapesEditor/Editor', ['page' => $page]);
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|string']);

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $i = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        $page = Page::create([
            'title' => $request->title,
            'slug'  => $slug,
            'html'  => $request->input('html', ''),
            'json'  => $request->input('json', []),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'page' => $page], 201);
        }

        return redirect()->route('pages.edit', $page->id);
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $i = 1;
        while (Page::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$i}";
            $i++;
        }

        $data = $request->validate([
            'title' => 'required|string',
            'html' => 'nullable|string',
            'json' => 'nullable|array',
        ]);

        $page->update([
            'title' => $data['title'],
            'slug'  => $slug,
            'html' => $data['html'] ?? $page->html,
            'json' => $data['json'] ?? $page->json,
        ]);

        return response()->json(['ok' => true, 'page' => $page]);
    }

    public function grapesLoad($id)
    {
        $page = Page::findOrFail($id);
        return response()->json([
            'html' => $this->normalizeNamedColors($page->html),
            'json' => $page->json ?? [],
        ]);
    }

    public function grapesStore(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $page->html = $request->input('html', $page->html);
        $page->json = $request->input('json', $page->json);
        $page->save();

        return response()->json(['status' => 'Page Added Successfull', 'page' => $page]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['file' => 'required|image|max:5120']);

        $path = $request->file('file')->store('public/uploads');
        $url = Storage::url($path);

        return response()->json(['status' => true, 'url' => $url]);
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return Inertia::render('Admin/GrapesEditor/RenderPage', ['page' => $page]);
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return back()->with('status', 'Page deleted!');
    }

    protected function normalizeNamedColors($html)
    {
        if (!is_string($html) || $html === '') return $html;
        $map = [
            'black' => '#000000',
            'white' => '#ffffff',
            'red'   => '#ff0000',
            'green' => '#00ff00',
            'blue'  => '#0000ff',
            'silver' => '#c0c0c0',
            'gray' => '#808080',
            'yellow' => '#ffff00',
        ];

        foreach ($map as $name => $hex) {
            $html = preg_replace('/(?<![#\w-])' . preg_quote($name, '/') . '(?![#\w-])/i', $hex, $html);
        }

        return $html;
    }

    protected function normalizeColorsInArray($arr)
    {
        $map = [
            'black' => '#000000',
            'white' => '#ffffff',
            'red'   => '#ff0000',
            'green' => '#00ff00',
            'blue'  => '#0000ff',
            'silver' => '#c0c0c0',
            'gray' => '#808080',
            'yellow' => '#ffff00',
        ];

        $walk = function (&$item) use (&$walk, $map) {
            if (is_array($item)) {
                foreach ($item as &$v) {
                    $walk($v);
                }
            } elseif (is_string($item)) {
                foreach ($map as $name => $hex) {
                    $item = preg_replace('/(?<![#\w-])' . preg_quote($name, '/') . '(?![#\w-])/i', $hex, $item);
                }
            }
        };

        $walk($arr);
        return $arr;
    }
}
