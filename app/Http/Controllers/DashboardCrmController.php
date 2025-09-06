<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Mail\PromotionMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // 👈 Tambahin ini bro

class DashboardCrmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.crm.index', [
            'title' => 'Promotion List',
            'promotions' => Promotion::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.create', ['title' => 'Add Promotion']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'product_link' => 'nullable|url',
        ]);

        $promotion = Promotion::create($validated);

        // 🔥 Send email & WhatsApp to all users
        $users = User::all();
        foreach ($users as $user) {
            // Send email
            if ($user->email) {
                Mail::to($user->email)->send(new PromotionMail($promotion));
            }

            // Send WhatsApp (if phone exists)
            if ($user->phone) {
                $message = "🎉 {$promotion->title} 🎉\n\n";
                $message .= "{$promotion->message}\n\n";

                if ($promotion->product_link) {
                    $message .= "👉 Cek produknya di sini: {$promotion->product_link}";
                }

                try {
                    $response = Http::withHeaders([
                        'Authorization' => config('services.fonnte.token'),
                    ])->asForm()->post(config('services.fonnte.url'), [
                        'target' => $user->phone,
                        'message' => $message,
                    ]);

                    if ($response->failed()) {
                        Log::error("Fonnte failed for {$user->phone}", [
                            'response' => $response->body(),
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send WA to {$user->phone}: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('crm.index')->with('success', 'Promotion created & sent successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $crm)
    {
        return view('dashboard.crm.edit', [
            'title' => 'Edit Promotion',
            'promotion' => $crm
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $crm)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'product_link' => 'nullable|url',
        ]);

        $crm->update($validated);

        return redirect()->route('crm.index')->with('success', 'Promotion updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $crm)
    {
        $crm->delete();

        return redirect()->route('crm.index')->with('success', 'Promotion deleted successfully!');
    }
}
