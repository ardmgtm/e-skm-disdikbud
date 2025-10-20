<?php
namespace App\Http\Controllers;

use App\Models\MsSkmIndicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\DataTableResponse;
use App\Http\Responses\JsonResponse;
use Throwable;
use Inertia\Inertia;

class MsSkmIndicatorController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('MsSkmIndicator/MsSkmIndicatorManageView');
    }

    public function dataTable(Request $request)
    {
        $query = MsSkmIndicator::query();
        return DataTableResponse::load($query);
    }
    public function show($id)
    {
        return MsSkmIndicator::findOrFail($id);
    }

    public function store(Request $request)
    {
        // $this->logActivity('Create new indicator'); // Uncomment jika ada logActivity
        $data = $request->validate([
            'indicator_name' => 'required|string|max:100',
        ]);
        DB::beginTransaction();
        try {
            $indicator = MsSkmIndicator::create($data);
            DB::commit();
            return JsonResponse::success('Success to create indicator');
        } catch (Throwable $e) {
            throw $e;
            DB::rollBack();
            return JsonResponse::failed('Failed to create indicator');
        }
    }

    public function update(Request $request, $id)
    {
        // $this->logActivity('Update indicator (id: ' . $id . ')'); // Uncomment jika ada logActivity
        $data = $request->validate([
            'indicator_name' => 'required|string|max:100',
        ]);
        $indicator = MsSkmIndicator::findOrFail($id);
        DB::beginTransaction();
        try {
            $indicator->update($data);
            DB::commit();
            return JsonResponse::success('Success to update indicator');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Failed to update indicator');
        }
    }

    public function delete(Request $request, $id)
    {
        // $this->logActivity('Delete indicator (id: ' . $id . ')'); // Uncomment jika ada logActivity
        $indicator = MsSkmIndicator::findOrFail($id);
        DB::beginTransaction();
        try {
            $indicator->delete();
            DB::commit();
            return JsonResponse::success('Success to delete indicator');
        } catch (Throwable $e) {
            dd($e);
            DB::rollBack();
            return JsonResponse::failed('Failed to delete indicator');
        }
    }
}
