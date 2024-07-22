<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Payment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{

    public function index()
    {
        $user = null;
        $contracts = [];

        if (auth('company')->check()) {
            $user = auth('company')->user();
            $contracts = Contract::where('employer_type', 'App\Models\Company')
                ->where('employer_id', $user->id)
                ->get();
        } elseif (auth('freelancer')->check()) {
            $user = auth('freelancer')->user();
            $contracts = Contract::where('freelancer_id', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('employer_type', 'App\Models\Freelancer')
                        ->where('employer_id', $user->id);
                })
                ->get();
        } else {
            return response()->json(['error' => 'Invalid user type'], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Contracts retrieved successfully.',
            'contracts' => $contracts,
        ], 200);
    }

    public function store(Request $request)
    {
        $user = null;
        $employerType = '';
        $jobType = '';
        if (auth('company')->check()) {
            $user = auth('company')->user();
            $employerType = 'App\Models\Company';
            $jobType = 'App\Models\CompanyJob';
        } elseif (auth('freelancer')->check()) {
            $user = auth('freelancer')->user();
            $employerType = 'App\Models\Freelancer';
            $jobType = 'App\Models\Job';
        } else {
            return response()->json(['error' => 'Invalid user type'], 400);
        }
        $validation = [
            'job_id' => 'required|integer',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'freelancer_id' => 'required|integer',
        ];
        if ($jobType === 'App\Models\Job') {
            $validation['start_date'] = 'required|date';
            $validation['end_date'] = 'required|date';
        }
        $request->validate($validation);
        $contract = [
            'job_type' => $jobType,
            'job_id' => $request->job_id,
            'employer_type' => $employerType,
            'employer_id' => $user->id,
            'freelancer_id' => $request->freelancer_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
        ];
        if ($jobType === 'App\Models\Job') {
            $contract['start_date'] = $request->start_date;
            $contract['end_date'] = $request->end_date;
        }
        $contract = Contract::create($contract);

        return response()->json(['contract' => $contract], 201);
    }

    public function acceptContract($id)
    {
        $contract = Contract::findOrFail($id);

        if (auth('freelancer')->id() !== $contract->freelancer_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $contract->update(['status' => 'active']);

        return response()->json(['message' => 'Contract accepted successfully'], 200);
    }


    public function show($id)
    {
        $contract = Contract::findOrFail($id);

        if ($this->isUserAuthorized($contract)) {
            return response()->json(['contract' => $contract], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    private function isUserAuthorized($contract)
    {
        $user = auth()->user();

        if (auth('company')->check()) {
            return $user->id === $contract->employer_id && $contract->employer_type === 'App\Models\Company';
        }

        if (auth('freelancer')->check()) {
            return $user->id === $contract->freelancer_id ||
                ($user->id === $contract->employer_id && $contract->employer_type === 'App\Models\Freelancer');
        }

        return false;
    }


    public function fund($contractId)
    {
        try {
            DB::beginTransaction();
            $contract = Contract::findOrFail($contractId);
            if ($contract->employer_id !== Auth::id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            $wallet = Wallet::where('owner_type', 'App\Models\Company')->where('owner_id', Auth::user()->id)->firstOrFail();
            if ($wallet->balance < $contract->amount) {
                $needed_balance = $contract->amount - $wallet->balance;
                return response()->json(['message' => "Insufficient balance. You need an additional $needed_balance to complete this purchase."], 401);
            } else {
                $wallet->balance -= $contract->amount;
                $payment = Payment::create([
                    'contract_id' => $contractId,
                    'amount' => $contract->amount,
                    'status' => 'escrow'
                ]);
                $contract->update(['status' => 'active']);
                return response()->json(['message' => 'Contract funded successfully', 'payment' => $payment], 200);
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response('error', $e->getMessage());
        }
    }


    public function releasePayment($contractId)
    {
        $contract = Contract::findOrFail($contractId);
        if ($contract->company_id !== Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $payment = $contract->payment;
        if ($payment->status !== 'escrow') {
            return response()->json(['message' => 'Invalid payment status'], 400);
        }
        $payment->update(['status' => 'released']);
        $contract->update(['status' => 'completed']);
        return response()->json(['message' => 'Payment released successfully'], 200);
    }
}
