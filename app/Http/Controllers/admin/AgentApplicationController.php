<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\AgentApplication;

class AgentApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $applications = AgentApplication::where('status', 'Pending')->get();
        return view('admin.agent-applications', compact('applications'));
    }

    public function approve($id)
    {
        $application = AgentApplication::findOrFail($id);
        $application->user->update(['role' => 'agent', 'is_approved' => true]);
        $application->update(['status' => 'Approved']);
        return redirect()->back()->with('success', 'Agen disetujui.');
    }

    public function reject($id)
    {
        $application = AgentApplication::findOrFail($id);
        $application->update(['status' => 'Rejected']);
        return redirect()->back()->with('success', 'Agen ditolak.');
    }
}