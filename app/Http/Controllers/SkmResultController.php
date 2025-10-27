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
}
