<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\CriteriaRange;
use App\Models\ScholarshipCriteria;
use App\Models\ScholarshipDocument;
use App\Models\Scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CriteriaPenyalurController extends Controller
{
    /**
     * Show the criteria management page.
     */
    public function index(Scholarships $scholarship)
    {
        $this->authorizeOwner($scholarship);

        $scholarship->load('criteria.ranges');

        return view('penyalur.beasiswa.criteria', compact('scholarship'));
    }

    /**
     * Save (sync) all criteria and their ranges.
     */
    public function store(Request $request, Scholarships $scholarship)
    {
        $this->authorizeOwner($scholarship);

        $criteriaInput = $request->input('criteria', []);

        $totalWeight = collect($criteriaInput)->sum(fn($c) => floatval($c['weight'] ?? 0));
        if (round($totalWeight, 2) !== 100.0) {
            return back()
                ->withInput()
                ->with('error', "Total bobot harus 100%. Saat ini: {$totalWeight}%");
        }

        DB::transaction(function () use ($criteriaInput, $scholarship) {

            $submittedCriteriaIds = [];

            foreach ($criteriaInput as $criteriaData) {
                $criteria = ScholarshipCriteria::updateOrCreate(
                    [
                        'id'            => $criteriaData['id'] ?? null,
                        'scholarship_id' => $scholarship->id,
                    ],
                    [
                        'scholarship_id' => $scholarship->id,
                        'name'           => $criteriaData['name'],
                        'type'           => $criteriaData['type'],
                        'weight'         => $criteriaData['weight'],
                        'input_type'     => $criteriaData['input_type'],
                    ]
                );

                $submittedCriteriaIds[] = $criteria->id;

                if ($criteriaData['input_type'] === 'range') {
                    $submittedRangeIds = [];
                    foreach ($criteriaData['ranges'] ?? [] as $rangeData) {
                        $range = CriteriaRange::updateOrCreate(
                            [
                                'id'          => $rangeData['id'] ?? null,
                                'criteria_id' => $criteria->id,
                            ],
                            [
                                'criteria_id' => $criteria->id,
                                'label'       => $rangeData['label'],
                                'min_value'   => ($rangeData['min_value'] ?? '') !== '' ? $rangeData['min_value'] : null,
                                'max_value'   => ($rangeData['max_value'] ?? '') !== '' ? $rangeData['max_value'] : null,
                                'score'       => $rangeData['score'],
                            ]
                        );
                        $submittedRangeIds[] = $range->id;
                    }
                    $criteria->ranges()->whereNotIn('id', $submittedRangeIds)->delete();
                } else {
                    $criteria->ranges()->delete();
                }
            }

            $scholarship->criteria()->whereNotIn('id', $submittedCriteriaIds)->each(function ($c) {
                $c->ranges()->delete();
                $c->delete();
            });
        });

        return redirect()
            ->route('penyalur.beasiswa.criteria', $scholarship->id)
            ->with('success', 'Kriteria SPK berhasil disimpan.');
    }

    public function storeDocuments(Request $request, Scholarships $scholarship)
    {
        $this->authorizeOwner($scholarship);

        $documentsInput = $request->input('documents', []);

        $request->validate(
            collect($documentsInput)->mapWithKeys(function ($doc, $i) {
                return [
                    "documents.{$i}.name"            => ['required', 'string', 'max:150'],
                    "documents.{$i}.is_required"      => ['required', 'in:0,1'],
                    "documents.{$i}.allowed_formats"  => ['nullable', 'string', 'max:100'],
                    "documents.{$i}.max_size_kb"      => ['nullable', 'integer', 'min:1'],
                    "documents.{$i}.description"      => ['nullable', 'string', 'max:255'],
                ];
            })->toArray(),
            collect($documentsInput)->mapWithKeys(function ($doc, $i) {
                return [
                    "documents.{$i}.name.required"   => 'Nama dokumen wajib diisi.',
                    "documents.{$i}.name.max"         => 'Nama dokumen maksimal 150 karakter.',
                    "documents.{$i}.is_required.in"   => 'Status dokumen tidak valid.',
                    "documents.{$i}.max_size_kb.min"  => 'Ukuran minimal 1 KB.',
                ];
            })->toArray()
        );

        DB::transaction(function () use ($documentsInput, $scholarship) {

            $submittedDocIds = [];

            foreach ($documentsInput as $order => $docData) {
                $doc = ScholarshipDocument::updateOrCreate(
                    [
                        'id'            => $docData['id'] ?? null,
                        'scholarship_id' => $scholarship->id,
                    ],
                    [
                        'scholarship_id'  => $scholarship->id,
                        'name'            => $docData['name'],
                        'is_required'     => (bool) $docData['is_required'],
                        'allowed_types' => !empty($docData['allowed_formats'])
                            ? array_map('trim', explode(',', $docData['allowed_formats']))
                            : null,
                        'max_size_kb'     => ($docData['max_size_kb'] ?? '') !== '' ? $docData['max_size_kb'] : null,
                        'description'     => $docData['description'] ?? null,
                        'order'           => $order,
                    ]
                );

                $submittedDocIds[] = $doc->id;
            }

            // Hapus dokumen yang dihapus dari form
            $scholarship->documents()->whereNotIn('id', $submittedDocIds)->delete();
        });

        return redirect()
            ->route('penyalur.beasiswa.criteria', $scholarship->id)
            ->with('success', 'Dokumen persyaratan berhasil disimpan.');
    }
    private function authorizeOwner(Scholarships $scholarship): void
    {
        if ($scholarship->penyalur_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke beasiswa ini.');
        }
    }
}
