<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageClientRequest;
use App\Models\HomepageClient;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HomepageClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Homepage/Client/Index', [
            'clients' => HomepageClient::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Homepage/Client/Form');
    }

    public function store(HomepageClientRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        }

        HomepageClient::create($data);

        return redirect()->route('admin.homepage.clients.index')
            ->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(HomepageClient $client)
    {
        return Inertia::render('Admin/Homepage/Client/Form', [
            'client' => $client,
        ]);
    }

    public function update(HomepageClientRequest $request, HomepageClient $client)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($client->logo) Storage::disk('public')->delete($client->logo);
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        } else {
            unset($data['logo']);
        }

        if ($request->boolean('clear_logo')) {
            if ($client->logo) Storage::disk('public')->delete($client->logo);
            $data['logo'] = null;
        }

        $client->update($data);

        return redirect()->route('admin.homepage.clients.index')
            ->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(HomepageClient $client)
    {
        if ($client->logo) Storage::disk('public')->delete($client->logo);
        $client->delete();

        return redirect()->route('admin.homepage.clients.index')
            ->with('success', 'Klien berhasil dihapus.');
    }
}
