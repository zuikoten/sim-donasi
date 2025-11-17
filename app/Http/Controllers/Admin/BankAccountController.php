<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankAccounts = BankAccount::orderBy('id', 'desc')->get();

        // Check if request is AJAX/JSON
        if (request()->wantsJson()) {
            return response()->json($bankAccounts);
        }

        // Return view with bank accounts data for server-side rendering
        return view('admin.bank-accounts.index', compact('bankAccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:255',
            'bank_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:512',
            'qris_image' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $data = $request->only(['bank_name', 'account_number', 'account_holder']);

        // Upload bank logo
        if ($request->hasFile('bank_logo')) {
            $data['bank_logo'] = $this->imageService->uploadBankLogo($request->file('bank_logo'));
        }

        // Upload QRIS image
        if ($request->hasFile('qris_image')) {
            $data['qris_image'] = $this->imageService->uploadQrisImage($request->file('qris_image'));
        }

        $data['is_active'] = $request->input('is_active', 1);

        $bank = BankAccount::create($data);

        return redirect()->back()->with('success', 'Bank account added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BankAccount $bankAccount)
    {
        return response()->json($bankAccount);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:255',
            'bank_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:512',
            'qris_image' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['bank_name', 'account_number', 'account_holder']);

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        // Replace Bank Logo if uploaded
        if ($request->hasFile('bank_logo')) {
            $data['bank_logo'] = $this->imageService->uploadBankLogo(
                $request->file('bank_logo'),
                $bankAccount->bank_logo
            );
        }

        // Replace QRIS image if uploaded
        if ($request->hasFile('qris_image')) {
            $data['qris_image'] = $this->imageService->uploadQrisImage(
                $request->file('qris_image'),
                $bankAccount->qris_image
            );
        }

        $bankAccount->update($data);

        return redirect()->back()->with('success', 'Bank account updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        // Delete images if exist
        if ($bankAccount->bank_logo) {
            $this->imageService->deleteImage($bankAccount->bank_logo);
        }

        if ($bankAccount->qris_image) {
            $this->imageService->deleteImage($bankAccount->qris_image);
        }

        $bankAccount->delete();

        return redirect()->back()->with('success', 'Bank account deleted successfully!');
    }
}
