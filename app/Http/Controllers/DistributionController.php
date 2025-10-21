<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Beneficiary;
use App\Models\Program;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin,admin');
    }

    public function index(Request $request)
    {
        // Ambil jumlah data per halaman
        $perPage = $request->input('perPage', 25);

        // Ambil filter sort
        $sort = $request->input('sort', 'default');

        // Query dasar
        $query = Distribution::query();

        // Urutkan berdasarkan pilihan user
        switch ($sort) {
            case 'nominal_desc':
                $query->orderBy('nominal_disalurkan', 'desc');
                break;
            case 'nominal_asc':
                $query->orderBy('nominal_disalurkan', 'asc');
                break;
            case 'tgl_desc':
                $query->orderBy('tanggal_penyaluran', 'desc');
                break;
            case 'tgl_asc':
                $query->orderBy('tanggal_penyaluran', 'asc');
                break;
        }

        // Jalankan pagination
        $distributions = $query->paginate($perPage)->appends($request->query());

        return view('admin.distributions.index', compact('distributions'));
    }

    public function create(Request $request)
    {
        $beneficiaries = Beneficiary::all();
        $programs = Program::where('status', 'aktif')->get();

        // Cek apakah ada beneficiary_id yang dipilih dari URL
        $selectedBeneficiary = null;
        if ($request->has('beneficiary_id')) {
            $selectedBeneficiary = Beneficiary::find($request->beneficiary_id);
        }

        // Kirim variabel $selectedBeneficiary ke view
        return view('admin.distributions.create', compact('beneficiaries', 'programs', 'selectedBeneficiary'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'program_id' => 'required|exists:programs,id',
            'nominal_disalurkan' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        // Check if program has enough funds
        $program = Program::find($request->program_id);
        if ($program->dana_terkumpul < $request->nominal_disalurkan) {
            return redirect()->back()
                ->with('error', 'Dana program tidak mencukupi untuk penyaluran ini.')
                ->withInput();
        }

        Distribution::create($request->all());

        return redirect()->route('distributions.index')
            ->with('success', 'Penyaluran donasi berhasil dicatat.');
    }

    public function show(Distribution $distribution)
    {
        $distribution->load(['beneficiary', 'program']);
        return view('admin.distributions.show', compact('distribution'));
    }

    public function edit(Distribution $distribution)
    {
        $beneficiaries = Beneficiary::all();
        $programs = Program::where('status', 'aktif')->get();
        return view('admin.distributions.edit', compact('distribution', 'beneficiaries', 'programs'));
    }

    public function update(Request $request, Distribution $distribution)
    {
        $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'program_id' => 'required|exists:programs,id',
            'nominal_disalurkan' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
            'deskripsi' => 'nullable|string',
        ]);

        // Get old distribution amount
        $oldAmount = $distribution->nominal_disalurkan;
        $newAmount = $request->nominal_disalurkan;
        $difference = $newAmount - $oldAmount;

        // Check if program has enough funds for the difference
        $program = Program::find($request->program_id);
        if ($program->dana_terkumpul < $difference) {
            return redirect()->back()
                ->with('error', 'Dana program tidak mencukupi untuk perubahan ini.')
                ->withInput();
        }

        $distribution->update($request->all());

        // Update program funds
        $program->dana_terkumpul -= $difference;
        $program->save();

        return redirect()->route('distributions.index')
            ->with('success', 'Data penyaluran donasi berhasil diperbarui.');
    }

    public function destroy(Distribution $distribution)
    {
        // Return funds to program
        $program = $distribution->program;
        $program->dana_terkumpul += $distribution->nominal_disalurkan;
        $program->save();

        $distribution->delete();

        return redirect()->route('distributions.index')
            ->with('success', 'Data penyaluran donasi berhasil dihapus.');
    }
}
