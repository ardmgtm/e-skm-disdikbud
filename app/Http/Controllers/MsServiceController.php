<?php
namespace App\Http\Controllers;

use App\Models\MsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\DataTableResponse;
use App\Http\Responses\JsonResponse;
use Throwable;
use Inertia\Inertia;

class MsServiceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('MsService/MsServiceManageView');
    }

    public function dataTable(Request $request)
    {
        $query = MsService::query();
        return DataTableResponse::load($query);
    }
    public function show($id)
    {
        return MsService::findOrFail($id);
    }

    public function store(Request $request)
    {
        // $this->logActivity('Create new service'); // Uncomment jika ada logActivity
        $data = $request->validate([
            'service_name' => 'required|string|max:100',
            'pic' => 'string|max:100',
        ]);
        DB::beginTransaction();
        try {
            $service = MsService::create($data);
            DB::commit();
            return JsonResponse::success('Success to create service');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Failed to create service');
        }
    }

    public function update(Request $request, $id)
    {
        // $this->logActivity('Update service (id: ' . $id . ')'); // Uncomment jika ada logActivity
        $data = $request->validate([
            'service_name' => 'required|string|max:100',
            'pic' => 'string|max:100',
        ]);
        $service = MsService::findOrFail($id);
        DB::beginTransaction();
        try {
            $service->update($data);
            DB::commit();
            return JsonResponse::success('Success to update service');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Failed to update service');
        }
    }

    public function delete(Request $request, $id)
    {
        // $this->logActivity('Delete service (id: ' . $id . ')'); // Uncomment jika ada logActivity
        $service = MsService::findOrFail($id);
        DB::beginTransaction();
        try {
            $service->delete();
            DB::commit();
            return JsonResponse::success('Success to delete service');
        } catch (Throwable $e) {
            DB::rollBack();
            return JsonResponse::failed('Failed to delete service');
        }
    }
}
