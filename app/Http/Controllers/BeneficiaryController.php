<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Program;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin,admin');
    }

    public function index(Request $request)
    {
        // Ambil nilai 'perPage' dari request, default = 25
        $perPage = $request->get('perPage', 25);

        // Ambil data penerima manfaat dengan pagination
        $beneficiaries = Beneficiary::orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.beneficiaries.index', compact('beneficiaries'));
    }

    public function create()
    {
        return view('admin.beneficiaries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        Beneficiary::create($request->all());

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Data penerima manfaat berhasil ditambahkan.');
    }

    public function show(Beneficiary $beneficiary)
    {
        $beneficiary->load('distributions.program');
        return view('admin.beneficiaries.show', compact('beneficiary'));
    }

    public function edit(Beneficiary $beneficiary)
    {
        return view('admin.beneficiaries.edit', compact('beneficiary'));
    }

    public function update(Request $request, Beneficiary $beneficiary)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'keterangan' => 'nullable|string',
        ]);

        $beneficiary->update($request->all());

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Data penerima manfaat berhasil diperbarui.');
    }

    public function destroy(Beneficiary $beneficiary)
    {
        $beneficiary->delete();

        return redirect()->route('beneficiaries.index')
            ->with('success', 'Data penerima manfaat berhasil dihapus.');
    }
}
