<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageClientRequest;
use App\Models\HomepageClient;
use App\Support\MediaUrl;
use Inertia\Inertia;

class HomepageClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Homepage/Client/Index', [
            'clients' => HomepageClient::orderBy('sort_order')->orderBy('id')->get()
                ->map(fn ($c) => [
                    ...$c->toArray(),
                    'logo' => MediaUrl::resolve($c->logo),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Homepage/Client/Form');
    }

    public function store(HomepageClientRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo_file')) {
            $data['logo'] = $request->file('logo_file')->store('clients', 'public');
        }
        unset($data['logo_file'], $data['clear_logo']);

        HomepageClient::create($data);

        return redirect()->route('admin.homepage.clients.index')
            ->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(HomepageClient $client)
    {
        return Inertia::render('Admin/Homepage/Client/Form', [
            'client' => [
                ...$client->toArray(),
                'logo' => MediaUrl::resolve($client->logo),
            ],
        ]);
    }

    public function update(HomepageClientRequest $request, HomepageClient $client)
    {
        $data = $request->validated();

        if ($request->hasFile('logo_file')) {
            MediaUrl::deleteIfLocal($client->logo);
            $data['logo'] = $request->file('logo_file')->store('clients', 'public');
        } elseif ($request->boolean('clear_logo')) {
            MediaUrl::deleteIfLocal($client->logo);
            $data['logo'] = null;
        } elseif (!empty($data['logo'])) {
            // URL mode — value already in $data
        } else {
            unset($data['logo']); // upload mode, no new file → keep existing
        }
        unset($data['logo_file'], $data['clear_logo']);

        $client->update($data);

        return redirect()->route('admin.homepage.clients.index')
            ->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(HomepageClient $client)
    {
        MediaUrl::deleteIfLocal($client->logo);
        $client->delete();

        return redirect()->route('admin.homepage.clients.index')
            ->with('success', 'Klien berhasil dihapus.');
    }
}
