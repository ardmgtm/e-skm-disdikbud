<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\MsSkmHeader;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\DataTableResponse;
use App\Http\Responses\JsonResponse;
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
        $header = MsSkmHeader::where('uuid', $uuid)->firstOrFail();
        return Inertia::render('Skm/SkmDetailView', [
            'skm_header' => $header,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
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
            'name' => 'required|string|max:100',
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
}
