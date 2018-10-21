<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        return view('report');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|min:4',
            'photo' => 'required|image|file'
        ]);

        // TODO: File validation
        // Make sure it's a valid photo

        $report = Report::create([
            'location' => $request->location,
            'ip_address' => $request->ip()
        ]);

        $path = $request->file('photo')->storeAs(
            'public', 'fireimg' . $report->id
        );

        flash('Successfully reported! It is now visible to other people.', 'success');

        return redirect('/list');
    }

    public function image(Report $report)
    {
        return response()->download(storage_path('app/public/fireimg' . $report->id));
    }
}
