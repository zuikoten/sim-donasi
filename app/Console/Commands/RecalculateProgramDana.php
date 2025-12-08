<?php

namespace App\Console\Commands;

use App\Models\Program;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateProgramDana extends Command
{
    protected $signature = 'program:recalculate-dana {--program_id=} {--fix}';

    protected $description = 'Recalculate dana_terkumpul for programs from verified donations and distributions';

    public function handle()
    {
        $this->info('Starting recalculation...');
        $this->info('Formula: dana_terkumpul = SUM(verified_donations) - SUM(distributions)');
        $this->newLine();

        $programId = $this->option('program_id');
        $shouldFix = $this->option('fix');

        // Query untuk mendapatkan data program, donasi terverifikasi, dan distribusi
        $query = DB::table('programs as p')
            ->leftJoin('donations as don', function ($join) {
                $join->on('p.id', '=', 'don.program_id')
                    ->where('don.status', '=', 'terverifikasi');
            })
            ->leftJoin('distributions as dis', 'p.id', '=', 'dis.program_id')
            ->select(
                'p.id',
                'p.nama_program',
                'p.dana_terkumpul as current_dana',
                DB::raw('COALESCE(SUM(DISTINCT don.id * don.nominal) / NULLIF(SUM(DISTINCT don.id), 0), 0) as total_donations'),
                DB::raw('COALESCE(SUM(DISTINCT dis.id * dis.nominal_disalurkan) / NULLIF(SUM(DISTINCT dis.id), 0), 0) as total_distributions')
            )
            ->groupBy('p.id', 'p.nama_program', 'p.dana_terkumpul');

        if ($programId) {
            $query->where('p.id', $programId);
        }

        $programs = $query->get();

        // Recalculate with proper aggregation
        $recalculatedPrograms = collect();
        foreach ($programs as $program) {
            $donations = DB::table('donations')
                ->where('program_id', $program->id)
                ->where('status', 'terverifikasi')
                ->sum('nominal');

            $distributions = DB::table('distributions')
                ->where('program_id', $program->id)
                ->sum('nominal_disalurkan');

            $calculated_dana = $donations - $distributions;

            $recalculatedPrograms->push((object)[
                'id' => $program->id,
                'nama_program' => $program->nama_program,
                'current_dana' => (float)$program->current_dana,
                'total_donations' => (float)$donations,
                'total_distributions' => (float)$distributions,
                'calculated_dana' => (float)$calculated_dana,
            ]);
        }

        $this->info('Found ' . $recalculatedPrograms->count() . ' program(s) to check.');
        $this->newLine();

        $discrepancies = 0;
        $fixed = 0;

        $headers = ['ID', 'Program', 'Current', 'Donations', 'Distributions', 'Calculated', 'Diff', 'Status'];
        $rows = [];

        foreach ($recalculatedPrograms as $program) {
            $difference = $program->current_dana - $program->calculated_dana;

            if (abs($difference) > 0.01) { // Tolerance untuk floating point
                $discrepancies++;
                $status = 'MISMATCH';

                if ($shouldFix) {
                    DB::table('programs')
                        ->where('id', $program->id)
                        ->update(['dana_terkumpul' => $program->calculated_dana]);

                    $status = 'FIXED';
                    $fixed++;
                }

                $rows[] = [
                    $program->id,
                    substr($program->nama_program, 0, 20),
                    number_format($program->current_dana, 0),
                    number_format($program->total_donations, 0),
                    number_format($program->total_distributions, 0),
                    number_format($program->calculated_dana, 0),
                    number_format($difference, 0),
                    $status
                ];
            }
        }

        if (empty($rows)) {
            $this->info('✓ All programs are accurate. No discrepancies found!');
        } else {
            $this->table($headers, $rows);
            $this->newLine();

            if ($shouldFix) {
                $this->info("✓ Fixed {$fixed} program(s) with discrepancies.");
            } else {
                $this->warn("Found {$discrepancies} program(s) with discrepancies.");
                $this->info('Run with --fix option to automatically correct them:');
                $this->comment('  php artisan program:recalculate-dana --fix');
            }
        }

        // Statistik tambahan
        $this->newLine();
        $this->info('=== Statistics ===');
        $totalPrograms = $recalculatedPrograms->count();
        $accuratePrograms = $totalPrograms - $discrepancies;
        $accuracy = $totalPrograms > 0 ? ($accuratePrograms / $totalPrograms) * 100 : 100;

        $totalDonations = $recalculatedPrograms->sum('total_donations');
        $totalDistributions = $recalculatedPrograms->sum('total_distributions');
        $totalDanaAktual = $recalculatedPrograms->sum('current_dana');
        $totalDanaSeharusnya = $recalculatedPrograms->sum('calculated_dana');

        $this->line("Total Programs: {$totalPrograms}");
        $this->line("Accurate: {$accuratePrograms}");
        $this->line("Discrepancies: {$discrepancies}");
        $this->line("Accuracy: " . number_format($accuracy, 2) . "%");
        $this->newLine();
        $this->line("Total Donations: Rp " . number_format($totalDonations, 0, ',', '.'));
        $this->line("Total Distributions: Rp " . number_format($totalDistributions, 0, ',', '.'));
        $this->line("Current Dana Terkumpul: Rp " . number_format($totalDanaAktual, 0, ',', '.'));
        $this->line("Should Be: Rp " . number_format($totalDanaSeharusnya, 0, ',', '.'));

        if (abs($totalDanaAktual - $totalDanaSeharusnya) > 0.01) {
            $this->newLine();
            $this->warn("⚠ Global discrepancy detected: Rp " . number_format($totalDanaAktual - $totalDanaSeharusnya, 0, ',', '.'));
        }

        return 0;
    }
}
