<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;

class AdminOwnerController extends Controller
{
    public function index()
    {
        $owners = User::where('role', 'owner')->with('hotel')->get();

        return view('admin.owners.index', compact('owners'));

    }

    public function create()
    {
        $hotels = Hotel::all();
        return view('admin.owners.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'hotel_id' => 'required|exists:hotels,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'role' => 'owner',
            'hotel_id' => $validated['hotel_id'],
        ]);

        return redirect()->route('admin.owners.index')->with('success', 'Owner berhasil ditambahkan!');
    }

    public function show($id)
    {
        $owner = User::where('role', 'owner')->with('hotel')->findOrFail($id);
        return view('admin.owners.show', compact('owner'));
    }

    public function edit($id)
    {
        $owner = User::where('role', 'owner')->findOrFail($id);
        $hotels = Hotel::all();
        return view('admin.owners.edit', compact('owner', 'hotels'));
    }

    public function update(Request $request, $id)
    {
        $owner = User::where('role', 'owner')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $owner->id,
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'hotel_id' => 'required|exists:hotels,id',
        ]);

        $owner->update($validated);

        return redirect()->route('admin.owners.index')->with('success', 'Data owner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $owner = User::where('role', 'owner')->findOrFail($id);
        $owner->delete();

        return redirect()->route('admin.owners.index')->with('success', 'Owner berhasil dihapus!');
    }
}
