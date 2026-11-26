<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrewManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Crew::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->orWhere('crew_id', 'LIKE', "%{$search}%")
                ->orWhere('rank_applied_for', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('rank')) {
            $query->where('rank_applied_for', $request->rank);
        }

        if ($request->filled('nation')) {
            $query->where('nationality', $request->nation);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $crews = $query->latest()->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('admin.crews._crew_list', compact('crews'))->render();
        }

        return view('admin.crews.index', compact('crews'));
    }

    public function view(Crew $crew){
        return view('admin.crews.view', compact('crew'));
    }

    public function correctionRequest(Crew $crew){
        return view('admin.crews.correction_request', compact('crew'));
    }

    public function correctionStore(Request $request, Crew $crew){
        $validated = $request->validate([
            'id' => 'nullable|array',
            'id.*' => 'nullable|integer',
            'missing_type' => ['required', 'array', 'min:1'],
            'missing_type.*' => ['required', 'string', 'max:100'],

            'missing_item' => ['required', 'array', 'min:1'],
            'missing_item.*' => ['required', 'string', 'max:255'],

            'remark' => ['nullable', 'array'],
            'remark.*' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $crew) {

            $submittedIds = [];

            foreach ($validated['missing_type'] as $index => $type) {

                if (!empty($validated['id'][$index])) {

                    // Existing item
                    $crewRequest = $crew->correctionItems()
                        ->findOrFail($validated['id'][$index]);

                } else {

                    // New item
                    $crewRequest = $crew->correctionItems()->make();
                }

                $crewRequest->missing_type = $type;
                $crewRequest->missing_item = $validated['missing_item'][$index];
                $crewRequest->remark = $validated['remark'][$index] ?? null;

                $crewRequest->save();

                $submittedIds[] = $crewRequest->id;
            }

            // Delete records removed from the submitted form
            $removedRecords = $crew->correctionItems()
                ->when(
                    !empty($submittedIds),
                    fn ($query) => $query->whereNotIn('id', $submittedIds)
                )
                ->when(
                    empty($submittedIds),
                    fn ($query) => $query
                )
                ->get();

            foreach ($removedRecords as $record) {
                $record->delete();
            }
        });

        $crew->update(['status' => 'Correction']);

        return back()->with('success', 'Data saved successfully!');
    }

    public function crewCorrectionsList(Request $request){
        $query = Crew::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                ->orWhere('mobile', 'LIKE', "%{$search}%")
                ->orWhere('crew_id', 'LIKE', "%{$search}%")
                ->orWhere('rank_applied_for', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('rank')) {
            $query->where('rank_applied_for', $request->rank);
        }

        if ($request->filled('nation')) {
            $query->where('nationality', $request->nation);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $crews = $query->latest()->where('status','correction')->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('admin.crews._crew_list', compact('crews'))->render();
        }

        return view('admin.crews.correction_list',compact('crews'));
    }

    public function print(Crew $crew){
        $crew->load([
            'nextOfKins',
            'documents',
            'services',
            'stcws',
        ]);

        return view('admin.crews.print', compact('crew'));
    }
}
