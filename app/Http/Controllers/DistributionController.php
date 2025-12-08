<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Beneficiary;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $query = Distribution::with(['beneficiary', 'program']);

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
            default:
                $query->orderBy('created_at', 'desc');
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
        $messages = [
            'beneficiary_id.required' => 'Penerima manfaat wajib dipilih.',
            'beneficiary_id.exists' => 'Penerima manfaat tidak valid.',
            'program_id.required' => 'Program wajib dipilih.',
            'program_id.exists' => 'Program tidak valid.',
            'nominal_disalurkan.required' => 'Nominal penyaluran wajib diisi.',
            'nominal_disalurkan.numeric' => 'Nominal harus berupa angka.',
            'nominal_disalurkan.min' => 'Nominal minimal Rp 1.000.',
            'tanggal_penyaluran.required' => 'Tanggal penyaluran wajib diisi.',
            'tanggal_penyaluran.date' => 'Format tanggal tidak valid.',
        ];

        $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'program_id' => 'required|exists:programs,id',
            'nominal_disalurkan' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
            'deskripsi' => 'nullable|string',
        ], $messages);

        DB::beginTransaction();
        try {
            // Check if program has enough funds
            $program = Program::lockForUpdate()->find($request->program_id);

            if ($program->dana_terkumpul < $request->nominal_disalurkan) {
                return redirect()->back()
                    ->with('error', 'Dana program tidak mencukupi. Dana tersedia: Rp ' . number_format($program->dana_terkumpul, 0, ',', '.'))
                    ->withInput();
            }

            // Create distribution - trigger akan otomatis mengurangi dana_terkumpul
            Distribution::create($request->all());

            DB::commit();
            return redirect()->route('distributions.index')
                ->with('success', 'Penyaluran donasi berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
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
        $messages = [
            'beneficiary_id.required' => 'Penerima manfaat wajib dipilih.',
            'beneficiary_id.exists' => 'Penerima manfaat tidak valid.',
            'program_id.required' => 'Program wajib dipilih.',
            'program_id.exists' => 'Program tidak valid.',
            'nominal_disalurkan.required' => 'Nominal penyaluran wajib diisi.',
            'nominal_disalurkan.numeric' => 'Nominal harus berupa angka.',
            'nominal_disalurkan.min' => 'Nominal minimal Rp 1.000.',
            'tanggal_penyaluran.required' => 'Tanggal penyaluran wajib diisi.',
            'tanggal_penyaluran.date' => 'Format tanggal tidak valid.',
        ];

        $request->validate([
            'beneficiary_id' => 'required|exists:beneficiaries,id',
            'program_id' => 'required|exists:programs,id',
            'nominal_disalurkan' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
            'deskripsi' => 'nullable|string',
        ], $messages);

        DB::beginTransaction();
        try {
            $oldProgramId = $distribution->program_id;
            $oldNominal = $distribution->nominal_disalurkan;
            $newProgramId = $request->program_id;
            $newNominal = $request->nominal_disalurkan;

            // Validasi dana tersedia
            if ($oldProgramId == $newProgramId) {
                // Program sama, cek selisih nominal
                $difference = $newNominal - $oldNominal;
                if ($difference > 0) {
                    $program = Program::lockForUpdate()->find($newProgramId);
                    // Dana yang tersedia = dana_terkumpul + nominal lama (karena akan dikembalikan)
                    $availableFunds = $program->dana_terkumpul + $oldNominal;

                    if ($availableFunds < $newNominal) {
                        return redirect()->back()
                            ->with('error', 'Dana program tidak mencukupi. Dana tersedia: Rp ' . number_format($availableFunds, 0, ',', '.'))
                            ->withInput();
                    }
                }
            } else {
                // Program berbeda, cek dana di program baru
                $newProgram = Program::lockForUpdate()->find($newProgramId);
                if ($newProgram->dana_terkumpul < $newNominal) {
                    return redirect()->back()
                        ->with('error', 'Dana program tujuan tidak mencukupi. Dana tersedia: Rp ' . number_format($newProgram->dana_terkumpul, 0, ',', '.'))
                        ->withInput();
                }
            }

            // Update distribution - trigger akan handle update dana_terkumpul
            $distribution->update($request->all());

            // Log perubahan
            \Log::info('Distribution Updated', [
                'distribution_id' => $distribution->id,
                'admin_id' => auth()->id(),
                'old_data' => [
                    'program_id' => $oldProgramId,
                    'nominal' => $oldNominal,
                ],
                'new_data' => [
                    'program_id' => $newProgramId,
                    'nominal' => $newNominal,
                ]
            ]);

            DB::commit();
            return redirect()->route('distributions.index')
                ->with('success', 'Data penyaluran donasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Distribution $distribution)
    {
        DB::beginTransaction();
        try {
            // Log sebelum delete
            \Log::info('Distribution Deleted', [
                'distribution_id' => $distribution->id,
                'admin_id' => auth()->id(),
                'data' => $distribution->toArray()
            ]);

            // Delete distribution - trigger akan otomatis mengembalikan dana
            $distribution->delete();

            DB::commit();
            return redirect()->route('distributions.index')
                ->with('success', 'Data penyaluran donasi berhasil dihapus dan dana dikembalikan ke program.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
