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
        $banks = BankAccount::all();
        return response()->json($banks);
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
        ]);

        $data = $request->only(['bank_name', 'account_number', 'account_holder']);

        // Upload logo
        if ($request->hasFile('bank_logo')) {
            $data['bank_logo'] = $this->imageService->uploadBankLogo($request->file('bank_logo'));
        }

        $bank = BankAccount::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Bank account added successfully!',
            'data' => $bank
        ]);
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
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['bank_name', 'account_number', 'account_holder']);

        if ($request->has('is_active')) {
            $data['is_active'] = $request->is_active;
        }

        // Upload new logo if provided
        if ($request->hasFile('bank_logo')) {
            $data['bank_logo'] = $this->imageService->uploadBankLogo($request->file('bank_logo'), $bankAccount->bank_logo);
        }

        $bankAccount->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Bank account updated successfully!',
            'data' => $bankAccount
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bank account deleted successfully!'
        ]);
    }
}
