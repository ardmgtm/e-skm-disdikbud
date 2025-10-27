<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MsSkmHeader;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\DataTableResponse;
use App\Http\Responses\JsonResponse;
use App\Models\VlEducation;
use App\Models\VlOccupation;
use App\Models\VlSkmIndicator;
use Throwable;

class SkmHeaderController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Skm/SkmManageView');
    }

    public function dataTable(Request $request)
    {
        $query = MsSkmHeader::query();
        return DataTableResponse::load($query);
    }

    public function show(Request $request, $uuid)
    {
        $header = MsSkmHeader::with(['services'])->where('uuid', $uuid)->firstOrFail();
        $skmIndicators = VlSkmIndicator::all();
        return Inertia::render('Skm/SkmDetailView', [
            'skm_header' => $header,
            'skm_indicators' => $skmIndicators,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        DB::beginTransaction();
        try {
            $header = MsSkmHeader::create($data);
            DB::commit();
            return JsonResponse::success('Berhasil membuat SKM');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Gagal membuat SKM');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        $header = MsSkmHeader::findOrFail($id);
        DB::beginTransaction();
        try {
            $header->update($data);
            DB::commit();
            return JsonResponse::success('Berhasil memperbarui SKM');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Gagal memperbarui SKM');
        }
    }

    public function delete(Request $request, $id)
    {
        $header = MsSkmHeader::findOrFail($id);
        DB::beginTransaction();
        try {
            $header->delete();
            DB::commit();
            return JsonResponse::success('Berhasil menghapus SKM');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Gagal menghapus SKM');
        }
    }

    
    public function updateDetail(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|string|max:500',
            'description' => 'required|string',
            'services' => 'array',
            'services.*.service_name' => 'required|string|max:100',
        ]);
        DB::beginTransaction();
        try {
            $header = MsSkmHeader::findOrFail($id);
            $header->update([
                'title' => $data['title'],
                'description' => $data['description'],
            ]);

            // Sinkronisasi layanan
            $serviceIds = [];
            foreach ($data['services'] as $service) {
                if (isset($service['id']) && $service['id']) {
                    // Update existing
                    $svc = $header->services()->where('id', $service['id'])->first();
                    if ($svc) {
                        $svc->update(['service_name' => $service['service_name']]);
                        $serviceIds[] = $svc->id;
                    }
                } else {
                    // Create new
                    $newSvc = $header->services()->create([
                        'service_name' => $service['service_name'],
                        'id_skm_header' => $header->id,
                    ]);
                    $serviceIds[] = $newSvc->id;
                }
            }
            // Delete removed services
            $header->services()->whereNotIn('id', $serviceIds)->delete();

            DB::commit();
            return JsonResponse::success('Berhasil update detail SKM');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Gagal update detail SKM');
        }
    }
    public function publish(Request $request, $uuid)
    {
        $header = MsSkmHeader::where('uuid', $uuid)->firstOrFail();
        DB::beginTransaction();
        try {
            $header->update(['is_published' => true]);
            DB::commit();
            return JsonResponse::success('Berhasil mempublikasikan SKM');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Gagal mempublikasikan SKM');
        }
    }

    public function preview(Request $request, $uuid)
    {
        $header = MsSkmHeader::with(['services'])->where('uuid', $uuid)->firstOrFail();
        $educations = VlEducation::all();
        $occupations = VlOccupation::all();
        $indicators = VlSkmIndicator::all();
        return Inertia::render('Skm/SkmSurveyView', [
            'skm_header' => $header,
            'is_preview' => true,
            'educations' => $educations,
            'occupations' => $occupations,
            'indicators' => $indicators,
        ]);
    }


    public function fillSurvey(Request $request, $uuid)
    {
        $header = MsSkmHeader::with(['services'])->where('uuid', $uuid)->first();
        if (!$header || !$header->is_published) {
            abort(404);
        }
        $educations = VlEducation::all();
        $occupations = VlOccupation::all();
        $indicators = VlSkmIndicator::all();
        return Inertia::render('Skm/SkmSurveyView', [
            'skm_header' => $header,
            'is_preview' => false,
            'educations' => $educations,
            'occupations' => $occupations,
            'indicators' => $indicators,
        ]);
    }

    public function submitSurvey(Request $request, $uuid)
    {
        $header = MsSkmHeader::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'respondent.gender' => 'required|boolean',
            'respondent.age' => 'required|integer|min:1|max:120',
            'respondent.id_education' => 'nullable|integer',
            'respondent.name_education' => 'nullable|string',
            'respondent.id_occupation' => 'nullable|integer',
            'respondent.name_occupation' => 'required|string',
            'respondent.name_occupation_other' => 'nullable|string',
            'resultHeader.id_service' => 'required|integer',
            'resultHeader.notes' => 'nullable|string',
            'answers' => 'required|array',
        ]);

    DB::beginTransaction();
        try {
            // Simpan header hasil survei
            $resultHeader = \App\Models\TrSkmResultHeader::create([
                'id_skm_header' => $header->id,
                'id_service' => $data['resultHeader']['id_service'],
                'timestamps' => now(),
                'notes' => $data['resultHeader']['notes'] ?? null,
            ]);

            // Simpan data responden
            $respondent = $data['respondent'];
            $respondent['id_skm_result_header'] = $resultHeader->id;
            \App\Models\TrRespondent::create($respondent);

            // Simpan jawaban survei
            foreach ($data['answers'] as $answer) {
                $answer['id_skm_result_header'] = $resultHeader->id;
                \App\Models\TrSkmResultAnswer::create($answer);
            }

            DB::commit();
            return JsonResponse::success('Berhasil submit survei');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Gagal submit survei', ['error' => $e->getMessage()]);
        }
    }
}
