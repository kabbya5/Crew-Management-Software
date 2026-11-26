<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(){
        $crews = DB::table('crews')->get();
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('crews', 'notifications'));
    }
}
