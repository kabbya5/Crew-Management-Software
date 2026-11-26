<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\CrewManagementController;
use App\Models\Crew;
use App\Models\User;
use App\Notifications\CrewActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Notification;

class CrewController extends Controller
{
    public function create(Request $request)
    {

        $user_id = auth()->id();

        if($request->crew_id){
            $crew = Crew::find($request->crew_id);
        }else{
            $crew = Crew::where('user_id', $user_id)->first();
            if(!$crew){
                $crew = new Crew();
            }

            if($request->admin_create){
                $crew = new Crew();
            }
        }

        if($crew->id){
            $crew->is_notify = 0;
            $crew->save();
        }

        return view('crews.create', compact('crew'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            // Personal Information
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'place_of_birth' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'rank_applied_for' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',

            // Contact Information
            'mobile' => 'required|string|max:30',
            'phone_country_code' => 'nullable|string|max:10',
            'phone_country_iso' => 'nullable|string|size:2',
            'email' => 'nullable|email|max:255',
            'current_location' => 'nullable|string|max:255',
            'available_from' => 'nullable|date',

            // Physical Measurements
            'height' => 'nullable|numeric|between:50,250',
            'weight' => 'nullable|numeric|between:20,300',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,Unknown / Not Provided',
            'shoe_size' => 'nullable|string|max:50',
            'shoe_size_standard' => 'nullable|in:EU,UK,US,CN,Other',
            'boiler_suit_size' => 'nullable|in:Not Measured,XS,S,M,L,XL,2XL,3XL,4XL,5XL,Custom',

            // Professional Information
            'english_proficiency' => 'nullable|string|max:255',
            'chinese_proficiency' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:5000',
        ]);

        $crewId = DB::transaction(function () {

            $lastCrew = Crew::orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $nextNumber = 1;

            if ($lastCrew && preg_match('/(\d+)$/', $lastCrew->crew_id, $matches)) {
                $nextNumber = (int) $matches[1] + 1;
            }

            return 'CR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });

        $validated['user_id'] = auth()->id();

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $folder = public_path('crew');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $filename = uniqid('crew_') . '.' . $file->getClientOriginalExtension();

            $file->move($folder, $filename);

            $validated['photo'] = 'crew/' . $filename;
        }

        $validated['crew_id'] = $crewId;

        $crew = Crew::create($validated);
        $admins = User::where('role', 'admin')->get();

        Notification::send(
            $admins,
            new CrewActivityNotification($crew,'A new Crew cv has been creted')
        );

        return redirect()->route('crews.create.document', $crew->id)->with('success', 'Crew profile saved successfully!');
    }

    public function update(Request $request, Crew $crew){

        $user_id = auth()->id();

        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $validated = $request->validate([
            // Personal Information
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'place_of_birth' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'rank_applied_for' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:Male,Female,Other',

            // Contact Information
            'mobile' => 'required|string|max:30',
            'phone_country_code' => 'nullable|string|max:10',
            'phone_country_iso' => 'nullable|string|size:2',
            'email' => 'nullable|email|max:255',
            'current_location' => 'nullable|string|max:255',
            'available_from' => 'nullable|date',

            // Physical Measurements
            'height' => 'nullable|numeric|between:50,250',
            'weight' => 'nullable|numeric|between:20,300',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-,Unknown / Not Provided',
            'shoe_size' => 'nullable|string|max:50',
            'shoe_size_standard' => 'nullable|in:EU,UK,US,CN,Other',
            'boiler_suit_size' => 'nullable|in:Not Measured,XS,S,M,L,XL,2XL,3XL,4XL,5XL,Custom',

            // Professional Information
            'english_proficiency' => 'nullable|string|max:255',
            'chinese_proficiency' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:5000',
        ]);

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $folder = public_path('crew');

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $filename = uniqid('crew_') . '.' . $file->getClientOriginalExtension();

            $file->move($folder, $filename);

            if ($crew->photo && file_exists(public_path($crew->photo))) {
                unlink(public_path($crew->photo));
            }

            $validated['photo'] = 'crew/' . $filename;
        }

        $crew->update($validated);

        $admins = User::where('role', 'admin')->get();

        Notification::send(
            $admins,
            new CrewActivityNotification($crew,'A new Crew cv has been updated')
        );

        return redirect()->route('crews.create.document', $crew->id)->with('success', 'Crew profile saved successfully!');
    }

    public function createDocument(Crew $crew)
    {
        $user_id = auth()->id();

        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $crewDocuments = $crew->documents;

    
        return view('crews.create_document', compact('crew', 'crewDocuments'));
    }

    public function storeDocument(Request $request, Crew $crew)
    {

        $user_id = auth()->id();

         if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $request->validate([
            'documents' => 'nullable|array',
            'documents.*.id' => 'nullable|integer',
            'documents.*.document_type' => 'required|string',
            'documents.*.custom_name' => 'nullable|string|max:255',
            'documents.*.document_number' => 'required|string|max:255',
            'documents.*.issue_date' => 'nullable|date',
            'documents.*.expiry_date' => 'nullable|date',
            'documents.*.no_expiry' => 'nullable|boolean',
            'documents.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);
        
        foreach ($request->input('documents', []) as $index => $document) {
            
            // Find existing document belonging to this crew.
            if (!empty($document['id'])) {

                $crewDocument = $crew->documents()
                    ->findOrFail($document['id']);

                if(!$crew->is_notify){
                    $admins = User::where('role', 'admin')->get();

                    Notification::send(
                        $admins,
                        new CrewActivityNotification($crew,'A new Crew cv Document has been updated')
                    );

                    $crew->is_notify = 1;
                    $crew->save();
                }

            } else {

                // New document.
                $crewDocument = $crew->documents()->make();
            }

            $noExpiry = !empty($document['no_expiry']);

            $crewDocument->document_type = $document['document_type'];
            $crewDocument->custom_name = $document['custom_name'] ?? null;
            $crewDocument->document_number = $document['document_number'] ?? null;
            $crewDocument->issue_date = $document['issue_date'] ?? null;
            $crewDocument->no_expiry = $noExpiry;
            $crewDocument->expiry_date = $noExpiry
                ? null
                : ($document['expiry_date'] ?? null);

            // Upload only when a new file is selected.
            if ($request->hasFile("documents.$index.file")) {

                $file = $request->file("documents.$index.file");

                $folder = public_path('crew/document');

                if (!file_exists($folder)) {
                    mkdir($folder, 0755, true);
                }

                // Delete old document
                if (
                    $crewDocument->file_path &&
                    file_exists(public_path($crewDocument->file_path))
                ) {
                    unlink(public_path($crewDocument->file_path));
                }

                // Upload new document
                $filename = uniqid('document_') . '.' . $file->getClientOriginalExtension();

                $file->move($folder, $filename);

                $crewDocument->file_path = 'crew/document/' . $filename;
            }
            $crewDocument->save();
        }

        return redirect()->route('crews.create.service.recored', $crew->id)->with('success', 'Crew profile saved successfully!');
    }

    public function createServiceRecored(Crew $crew)
    {
        $user_id = auth()->id();

        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $crewServices = $crew->services;

        return view('crews.create_service', compact('crew', 'crewServices'));

    }

    public function storeServiceRecored(Request $request, Crew $crew)
    {
        $user_id = auth()->id();

        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $validated = $request->validate([
            'services' => 'nullable|array',
            'services.*.id' => 'nullable|integer',
            'services.*.rank' => 'required|string|max:255',
            'services.*.vessel_name' => 'required|string|max:255',
            'services.*.vessel_type' => 'required|string|max:255',
            'services.*.dwt_bhp' => 'nullable|string|max:255',
            'services.*.imo_number' => 'nullable|string|max:255',
            'services.*.vessel_flag' => 'nullable|string|max:255',
            'services.*.main_engine' => 'nullable|string|max:255',
            'services.*.sign_on' => 'nullable|date',
            'services.*.sign_off' => 'nullable|date|after_or_equal:services.*.sign_on',
        ]);

        DB::transaction(function () use ($validated, $crew) {

            foreach ($validated['services'] ?? [] as $service) {

                if (!empty($service['id'])) {

                    // Update existing record belonging to this crew
                    $crewService = $crew->services()
                        ->findOrFail($service['id']);

                    $crewService->update($service);

                        if(!$crew->is_notify){
                            $admins = User::where('role', 'admin')->get();

                            Notification::send(
                                $admins,
                                new CrewActivityNotification($crew,'A new Crew service record has been updated')
                            );

                            $crew->is_notify = 1;
                            $crew->save();
                        }

                } else {

                    // Create new record
                    $crew->services()->create($service);
                }
            }
        });

        return redirect()
            ->route('crews.create.stcw', $crew->id)
            ->with('success', 'Sea service records saved successfully!');
    }

    public function createStcw(Crew $crew)
    {
        $user_id = auth()->id();
        
        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }
        $crewStcws = $crew->stcws;

        return view('crews.create_stcw', compact('crew', 'crewStcws'));

    }

    public function storeStcw(Request $request, Crew $crew)
    {
        if (auth()->id() != $crew->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'stcws' => 'nullable|array',
            'stcws.*.id' => 'nullable|integer',
            'stcws.*.course_name' => 'required|string|max:255',
            'stcws.*.certificate_number' => 'nullable|string|max:255',
            'stcws.*.issue_date' => 'nullable|date',
            'stcws.*.expiry_date' => 'nullable|required_if:stcws.*.no_expiry,0|date',
            'stcws.*.no_expiry' => 'nullable|boolean',
            'stcws.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        // Validate expiry date against issue date.
        foreach ($validated['stcws'] ?? [] as $index => $row) {
            if (
                empty($row['no_expiry']) &&
                !empty($row['issue_date']) &&
                !empty($row['expiry_date']) &&
                $row['expiry_date'] < $row['issue_date']
            ) {
                throw ValidationException::withMessages([
                    "stcws.$index.expiry_date" => 'Expiry date must be after or equal to issue date.',
                ]);
            }
        }

        DB::transaction(function () use ($request, $crew, $validated) {

            $submittedIds = [];

            foreach ($validated['stcws'] ?? [] as $index => $row) {

                $data = [
                    'course_name' => $row['course_name'],
                    'certificate_number' => $row['certificate_number'] ?? null,
                    'issue_date' => $row['issue_date'] ?? null,
                    'no_expiry' => !empty($row['no_expiry']),
                    'expiry_date' => !empty($row['no_expiry'])
                        ? null
                        : ($row['expiry_date'] ?? null),
                ];

                if (!empty($row['id'])) {
                    $stcw = $crew->stcws()->findOrFail($row['id']);
                      if(!$crew->is_notify){
                        $admins = User::where('role', 'admin')->get();

                        Notification::send(
                            $admins,
                            new CrewActivityNotification($crew,'A new Crew cv stcw has been updated')
                        );

                        $crew->is_notify = 1;
                        $crew->save();
                    }
                } else {
                    $stcw = $crew->stcws()->make();
                }

                if ($request->hasFile("stcws.$index.file")) {

                    $file = $request->file("stcws.$index.file");

                    // Public STCW folder
                    $folder = public_path('crew/stcw');

                    if (!file_exists($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    // Delete old file
                    if (
                        $stcw->file_path &&
                        file_exists(public_path($stcw->file_path))
                    ) {
                        unlink(public_path($stcw->file_path));
                    }

                    // Upload new file
                    $filename = uniqid('stcw_') . '.' . $file->getClientOriginalExtension();

                    $file->move($folder, $filename);

                    // Save relative path
                    $data['file_path'] = 'crew/stcw/' . $filename;
                }

                $stcw->fill($data);
                $stcw->save();

                $submittedIds[] = $stcw->id;
            }

            // Delete records removed from the form.
            $removedRecords = $crew->stcws()
                ->whereNotIn('id', $submittedIds)
                ->get();

            foreach ($removedRecords as $record) {
                if ($record->file_path && file_exists(public_path($record->file_path))) {
                    unlink(public_path($record->file_path));
                }

                $record->delete();
            }
        });

        return redirect()
            ->route('crews.create.contact', $crew->id)
            ->with('success', 'STCW certificates saved successfully!');
    }

    public function createContact(Crew $crew)
    {
        $user_id = auth()->id();
        
        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $crewContacts = $crew->nextOfKins;

        return view('crews.create_contact', compact('crew', 'crewContacts'));

    }


    public function storeContact(Request $request, Crew $crew)
    {
        $user_id = auth()->id();
        
        if($user_id != $crew->user_id && auth()->user()->role != 'admin'){
            return back();
        }

        $validated = $request->validate([
            'contacts' => 'required|array|min:1',

            'contacts.*.id' => 'nullable|integer',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.relation' => 'required|string|max:100',
            'contacts.*.mobile' => 'required|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.address' => 'nullable|string',
            'contacts.*.priority' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $crew) {

            $submittedIds = [];
            $primaryAssigned = false;

            foreach ($validated['contacts'] as $row) {

                // UPDATE existing contact
                if (!empty($row['id'])) {

                    $contact = $crew->nextOfKins()
                        ->findOrFail($row['id']);

                    if(!$crew->is_notify){
                        $admins = User::where('role', 'admin')->get();

                        Notification::send(
                            $admins,
                            new CrewActivityNotification($crew,'A new Crew emergency contact has been updated')
                        );

                        $crew->is_notify = 1;
                        $crew->save();
                    }

                } else {

                    // CREATE new contact
                    $contact = $crew->nextOfKins()->make();
                }

                // Only one primary contact allowed
                $priority = 'alternate';

                if ($row['priority'] === 'primary' && !$primaryAssigned) {
                    $priority = 'primary';
                    $primaryAssigned = true;
                }

                $contact->fill([
                    'full_name' => $row['name'],
                    'relationship' => $row['relation'] ?? null,
                    'mobile' => $row['mobile'] ?? null,
                    'email' => $row['email'] ?? null,
                    'address' => $row['address'] ?? null,
                    'priority' => $priority,
                ]);

                $contact->save();

                $submittedIds[] = $contact->id;
            }

            // DELETE contacts removed from the form
            $removedContacts = $crew->nextOfKins()
                ->whereNotIn('id', $submittedIds)
                ->get();

            foreach ($removedContacts as $contact) {
                $contact->delete();
            }
        });

        return redirect()
            ->route('crews.view', $crew->id)
            ->with('success', 'Crew contacts saved successfully!');
    }

    public function view(Crew $crew){
        if (auth()->id() != $crew->user_id) {
            abort(403);
        }
        return view('crews.view', compact('crew'));
    }

    public function crewCorrection(Crew $crew){
        if (auth()->id() != $crew->user_id) {
            abort(403);
        }
        return view('crews.correction', compact('crew'));
    }

}
