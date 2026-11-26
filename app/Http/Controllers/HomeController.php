<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $crew = Crew::where('user_id', auth()->id())->first();
        return view('home', compact('crew'));
    }

    public function account(){
        $crew = Crew::where('user_id', auth()->id())->first();
        return view('account',compact('crew'));
    }

    public function phoneUpdate(Request $request){
        $user = auth()->user();

        $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'current_password' => ['required', 'string'],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        // Update phone number
        $user->phone = $request->phone;
        $user->save();

        return back()->with('success', 'Login number updated successfully.');
    }

    public function documentView(Crew $crew){
        if (auth()->id() != $crew->user_id) {
            abort(403);
        }

        return view('crews.document_view', compact('crew'));
    }

    public function stcwView(Crew $crew){
        if (auth()->id() != $crew->user_id) {
            abort(403);
        }

        return view('crews.stcw_view', compact('crew'));
    }

    public function seaServiceView(Crew $crew){
        if (auth()->id() != $crew->user_id) {
            abort(403);
        }

        return view('crews.sea_service_view', compact('crew'));
    }

    public function passwordUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
