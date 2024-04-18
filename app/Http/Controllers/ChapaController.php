<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WithdrawalRequest;
use Chapa\Chapa\Facades\Chapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChapaController extends Controller
{
    protected $reference;

    public function __construct()
    {
        $this->reference = Chapa::generateReference();
    }

    public function initialize(Request $request)
    {
        $reference = $this->reference;
        $amount = $request->input('amount');
        $phoneNumber = $request->input('phone');

        $data = [
            'amount' => $amount,
            'email' => $reference . '@gmail.com',
            'tx_ref' => $reference,
            'phone_number' => '0' . $phoneNumber,
            'currency' => 'ETB',
            'return_url' => route('callback', [$reference]),
            'callback_url' => route('callback', [$reference]),
            'first_name' => Auth::user()->name,
            'last_name' => Auth::user()->name,
            'customization' => [
                'title' => 'Chapa payment',
                'description' => 'test payment',
            ],
        ];

        // dd($data);

        $payment = Chapa::initializePayment($data);
        // dd($payment);

        if ($payment['status'] !== 'success') {
            // notify something went wrong
            return;
        }

        return redirect($payment['data']['checkout_url']);
    }

    public function callback($reference)
    {

        $data = Chapa::verifyTransaction($reference);

        if ($data['status'] == 'success') {
            // Payment successful, save details to your database
            // Example: Save transaction reference, amount, and other relevant data
            $existingTransaction = Transaction::where('reference', $data['data']['tx_ref'])->first();
            if ($existingTransaction) {
                // Increment the amount for the existing transaction
                $existingTransaction->amount += $data['data']['amount'];

                $existingTransaction->save();
            } else { $transaction = new Transaction();
                $transaction->reference = $data['data']['tx_ref'];
                $transaction->transaction_id = $data['data']['tx_ref'];
                $transaction->amount = $data['data']['amount'];
                $transaction->status = 1;
                $transaction->user_id = Auth::id();
                // Add other relevant fields
                $transaction->save();}
            $totalDepositedAmount = Transaction::sum('amount');

            // Redirect back to your site with success message
            return redirect('/deposit')->with('success', 'Payment successful! Amount deposited: ' . $data['data']['amount'] . 'ETB');
        } else {
            // Payment failed
            dd($data);
            return redirect('/deposit')->with('error', 'Sorry, payment was not sucessful');
        }
    }

    public function withdrawOptions()
    {
        try {
            // Retrieve bearer token from .env file
            $bearerToken = env('CHAPA_SECRET_KEY');

            // Make GET request to fetch withdraw options with bearer token in headers
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken,
            ])->get('https://api.chapa.co/v1/banks');

            // dd($bearerToken);

            // Check if the request was successful and if data is available
            if ($response->successful() && isset($response['data'])) {
                $withdrawOptions = $response['data'];
                $status = 'success'; // Set status to success
                return view('withdraw', compact('withdrawOptions', 'status'));
            } else {
                // Handle error if API request fails or data is not available
                $error = 'Failed to fetch withdraw options.';
                $status = 'error'; // Set status to error
                return view('withdraw', compact('error', 'status'));
            }
        } catch (\Exception $e) {
            // Handle exception if any error occurs during the request
            $error = 'An error occurred while fetching withdraw options.';
            $status = 'error'; // Set status to error
            return view('withdraw', compact('error', 'status'));
        }
    }

    public function initiateWithdrawal(Request $request)
    {

        $amount = $request->input('amount');
        $phoneNumber = $request->input('phoneNumber');
        $bankCode = $request->input('optionId');
        $reference = $this->generateUUID();

        // Validate the request data
        // $validatedData = $request->validate([
        //     'account_number' => 'required|numeric',
        //     'amount' => 'required|numeric',
        //     'bank_code' => 'required|numeric',
        // ]);

        // Prepare the data for the API request
        $data = [
            'account_name' => Auth::user()->name,
            // 'account_number' => $phoneNumber,
            'account_number' => '0'.Auth::user()->phone,
            'amount' => $amount,
            'currency' => 'ETB',
            'reference' => $reference,
            'bank_code' => $bankCode,
        ];
     

        $bearerToken = env('CHAPA_SECRET_KEY');

        // Make the API request using Guzzle or any other HTTP client library
        $response = Http::withToken($bearerToken)->post('https://api.chapa.co/v1/transfers', $data);

        // dd($response->body());
        // Check if the request was successful
        if ($response->successful()) {
            $responseBody = $response->body();

// Decode the JSON string
            $data = json_decode($responseBody, true);
            if($data['status'] == 'success'){

                $transaction = new Transaction();
                $transaction->user_id = Auth::id();
                $transaction->amount = -$amount; // Subtract the withdrawal amount
                $transaction->reference = $reference;
                $transaction->transaction_id = $reference;
                $transaction->status = 1;
                $transaction->save();

                 // Create a withdrawal request
        $withdrawalRequest = new WithdrawalRequest();
        $withdrawalRequest->user_id = Auth::id();
        $withdrawalRequest->amount = $amount;
        // $withdrawalRequest->status = $responseData['status'];
        $withdrawalRequest->reference = $reference;
        $withdrawalRequest->save();

        $this->verifyTransaction($reference);
            // Return a JSON response with the success message
            // return response()->json(['message' => 'Withdrawal initiated successfully']);
            return redirect('/withdraw')->with('withdrawSuccess', $data['message'] ?? 'Withdrawal initiated successfully');
            } else {
                return redirect('/withdraw')->with('withdrawError', $data['message'] ?? 'Failed to initiate withdrawal');
            }
        } else {
            // Return a JSON response with the error message
            return response()->json(['withdrawError' => 'Failed to initiate withdrawal'], $response->status());
        }
    }

    public function verifyTransaction($tx_ref)
{
    try {
        // Retrieve bearer token from .env file
        $bearerToken = env('CHAPA_SECRET_KEY');

        // Make GET request to verify transaction with bearer token in headers
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $bearerToken,
        ])->get('https://api.chapa.co/v1/transfers/verify/' . $tx_ref);

        // Check if the request was successful and if data is available
        if ($response->successful() && isset($response['status']) && isset($response['data'])) {
            if ($response['status'] === 'success') {
                // Update withdrawal request status to approved
                WithdrawalRequest::where('reference', $tx_ref)->update(['status' => 'approved']);
                return redirect('/withdraw')->with('withdrawSuccess', 'Withdrawal request approved successfully');
            } else {
                $transaction = Transaction::where('reference', $tx_ref)->first();

                if ($transaction) {
                    // Remove the deducted amount from the balance
                    $transaction->delete();
                }
                // Update withdrawal request status to rejected
                WithdrawalRequest::where('reference', $tx_ref)->update(['status' => 'rejected']);
                return redirect('/withdraw')->with('withdrawError', 'Withdrawal request rejected');
            }
        } else {
            $transaction = Transaction::where('reference', $tx_ref)->first();

            if ($transaction) {
                // Remove the deducted amount from the balance
                $transaction->delete();
            }
            // Update withdrawal request status to rejected
            WithdrawalRequest::where('reference', $tx_ref)->update(['status' => 'rejected']);
            return redirect('/withdraw')->with('withdrawError', 'Withdrawal request rejected');
        }
    } catch (\Exception $e) {
        // Handle exception if any error occurs during the request
        return redirect('/withdraw')->with('withdrawError', 'An error occurred while verifying withdrawal');
    }
}


// Function to generate a UUID (Universally Unique Identifier)
    private function generateUUID()
    {
        return uniqid();
    }

}
