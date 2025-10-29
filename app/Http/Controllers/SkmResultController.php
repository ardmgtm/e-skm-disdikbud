<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrRespondent;
use App\Models\TrSkmResultHeader;
use App\Models\MsSkmHeader;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\JsonResponse;

class SkmResultController extends Controller
{
    public function respondents(Request $request, $skmHeaderId)
    {
        $header = MsSkmHeader::findOrFail($skmHeaderId);
        $query = TrSkmResultHeader::where('id_skm_header', $header->id)
            ->with(['respondent', 'service']);

        $dataLoad = \App\Services\DataTableAdapter::load($query, request());
        return response()->json($dataLoad);
    }

    public function reportDashboard(Request $request, $skmHeaderId)
    {
        $header = MsSkmHeader::findOrFail($skmHeaderId);
        $resultHeaders = TrSkmResultHeader::where('id_skm_header', $header->id)->pluck('id');
        $respondents = TrRespondent::whereIn('id_skm_result_header', $resultHeaders)->get();

        $total = $respondents->count();
        $avgAge = $total > 0 ? round($respondents->avg('age'), 1) : 0;
        $gender = [
            'male' => $respondents->where('gender', true)->count() + $respondents->where('gender', 1)->count(),
            'female' => $respondents->where('gender', false)->count() + $respondents->where('gender', 0)->count(),
        ];
        // Pendidikan
        $educationAgg = $respondents->groupBy('id_education')->map(function ($group, $id) {
            $first = $group->first();
            return [
                'id' => $id,
                'name' => $first->name_education ?? 'Lainnya',
                'count' => $group->count(),
            ];
        })->values()->all();

        // Pekerjaan
        $occupationAgg = $respondents->groupBy('id_occupation')->map(function ($group, $id) {
            $first = $group->first();
            return [
                'id' => $id,
                'name' => $first->name_occupation ?? 'Lainnya',
                'count' => $group->count(),
            ];
        })->values()->all();

        // Layanan/Service
        $serviceAgg = $respondents->groupBy(function ($item) {
            return optional($item->skmResultHeader->service)->id;
        })->map(function ($group, $id) {
            $first = $group->first();
            $serviceName = optional(optional($first->skmResultHeader)->service)->service_name ?? 'Lainnya';
            return [
                'id' => $id,
                'name' => $serviceName,
                'count' => $group->count(),
            ];
        })->values()->all();

        // Rata-rata indikator
        $resultHeaderIds = $resultHeaders;
        $answers = \App\Models\TrSkmResultAnswer::whereIn('id_skm_result_header', $resultHeaderIds)->get();
        $indicatorAgg = $answers->groupBy('id_skm_indicator')->map(function ($group, $id) {
            $first = $group->first();
            $indicatorName = $first->desc_skm_indicator ?? optional($first->indicator)->indicator_name ?? 'Lainnya';
            $avgValue = $group->avg('value');
            $score = round(($avgValue ?? 0) * 25);
            return [
                'id' => $id,
                'name' => $indicatorName,
                'avg_value' => round($avgValue, 2),
                'count' => $group->count(),
                'score' => $score,
            ];
        })->values()->all();

        // Rata-rata dan skor per layanan
        // 1. Ambil semua layanan
        $serviceAvgAgg = collect();
        $serviceGroups = $respondents->groupBy(function ($item) {
            return optional($item->skmResultHeader->service)->id;
        });
        foreach ($serviceGroups as $serviceId => $group) {
            $serviceName = optional(optional($group->first()->skmResultHeader)->service)->service_name ?? 'Lainnya';
            // Hitung rata-rata per responden untuk layanan ini
            $respondentIds = $group->pluck('id');
            $resultHeaderIds = $group->pluck('id_skm_result_header');
            // Ambil jawaban untuk responden di layanan ini
            $answers = \App\Models\TrSkmResultAnswer::whereIn('id_skm_result_header', $resultHeaderIds)->get();
            // Hitung rata-rata per responden
            $avgPerRespondent = $answers->groupBy('id_skm_result_header')->map(function ($ansGroup) {
                return $ansGroup->avg('value');
            });
            $serviceAvg = $avgPerRespondent->count() > 0 ? round($avgPerRespondent->avg(), 2) : 0;
            $score = round($serviceAvg * 25);
            $serviceAvgAgg->push([
                'id' => $serviceId,
                'name' => $serviceName,
                'avg_value' => $serviceAvg,
                'count' => $group->count(),
                'score' => $score,
            ]);
        }

        return response()->json([
            'data' => [
                'total_respondents' => $total,
                'avg_age' => $avgAge,
                'gender' => $gender,
                'education' => $educationAgg,
                'occupation' => $occupationAgg,
                'service' => $serviceAgg,
                'indicator_avg' => $indicatorAgg,
                'service_avg' => $serviceAvgAgg,
            ]
        ]);
    }
}
