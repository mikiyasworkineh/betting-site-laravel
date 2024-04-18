@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title text-dark">Withdraw</h5>
                </div>
                @php
                    $totalDepositedAmount = App\Models\Transaction::where('user_id', Auth::id())->sum('amount');
                @endphp
                <div class="card-body text-dark">
                    @if(session('withdrawSuccess'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('withdrawSuccess') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('withdrawError'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('withdrawError') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @php
                        $availableBalance = App\Models\Transaction::where('user_id', Auth::id())->sum('amount');
                    @endphp

                    {{-- Display withdraw options --}}
                    @if(isset($withdrawOptions) && count($withdrawOptions) > 0)
                        <h6>Select a Withdraw Option:</h6>
                        <div class="row">
                            @foreach($withdrawOptions as $option)
                                @if($option['slug'] === 'telebirr' || $option['slug'] === 'cbebirr')
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $option['name'] }}</h6>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @if ($option['slug'] === 'telebirr')
        <img src="telebirr-logo.jpeg" alt="Telebirr Logo" style="max-width: 50%; height: auto;">
    @else ($option['slug'] === 'cbebirr')
        <img src="cbebirr-logo.jpeg" alt="CBEbirr Logo" style="max-width: 50%; height: auto;">
    @endif
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $option['id'] }}">
                                                            Withdraw
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="exampleModal{{ $option['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Withdraw</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('withdraw.initiate') }}" method="POST" id="withdrawForm{{ $option['id'] }}">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="phoneNumber{{ $option['id'] }}" class="form-label">Phone Number</label>
                                                                        <input type="text" class="form-control" id="phoneNumber{{ $option['id'] }}" name="phoneNumber" pattern="0[0-9]{9}" maxlength="10" required>
                                                                        <div class="invalid-feedback">Please enter a valid phone number starting with 0.</div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="withdrawAmount{{ $option['id'] }}" class="form-label">Amount</label>
                                                                        <input type="number" class="form-control" id="withdrawAmount{{ $option['id'] }}" name="amount" min="5" max="5000" step="0.01" required 
                                                                               oninput="handleAmountInput('withdrawAmount{{ $option['id'] }}', 'withdrawBtn{{ $option['id'] }}', {{ $availableBalance }})">
                                                                        <small class="text-muted">Minimum: 5 ETB, Maximum: 5000 ETB</small>
                                                                        @if(isset($availableBalance))
                                                                            <p>Available Balance: {{ $availableBalance }} ETB</p>
                                                                        @endif
                                                                    </div>
                                                                    <input type="hidden" name="optionId" value="{{ $option['id'] }}">
                                                                    <p class="text-danger mt-2" id="errorMessage{{ $option['id'] }}"></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" id="withdrawBtn{{ $option['id'] }}" class="btn btn-primary withdraw-btn" 
                                                                            data-option-id="{{ $option['id'] }}" disabled>Withdraw</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p>No withdraw options available.</p>
                    @endif

                    {{-- Powered by Chapa --}}
                    <div class="mt-3 text-center text-muted">
                        <p class="mb-1">Powered by <img src="chapa-logo.png" alt="Chapa Logo" width="100"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($withdrawalStatus))
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if($withdrawalStatus == 'success')
                    <div class="alert alert-success" role="alert">
                        Withdrawal initiated successfully.
                    </div>
                @elseif($withdrawalStatus == 'error')
                    <div class="alert alert-danger" role="alert">
                        Failed to initiate withdrawal. Please try again.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

@php
    
    // Fetch withdrawal requests for the current user
    $userId = Auth::id();
    $withdrawalRequests = \App\Models\WithdrawalRequest::where('user_id', $userId)->get();
@endphp

@if($withdrawalRequests->isNotEmpty())
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h5>Your Withdrawal Requests</h5>
                <table class="table table-dark">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawalRequests as $request)
                        <tr>
                            <td>{{ $request->reference }}</td>
                            <td>{{ $request->amount }}</td>
                            <td style="color: 
                                @if($request->status == 'pending') 
                                    orange;
                                @elseif($request->status == 'approved') 
                                    green;
                                @else 
                                    red;
                                @endif">
                                {{ ucfirst($request->status) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<script>
    function handleAmountInput(amountInputId, withdrawButtonId, availableBalance) {
        var amountInput = document.getElementById(amountInputId);
        var withdrawButton = document.getElementById(withdrawButtonId);
        var amount = parseFloat(amountInput.value);

        // Validate amount
        var isValidAmount = amount >= 5 && amount <= availableBalance && !isNaN(amount);

        // Enable/disable withdraw button based on validation results
        withdrawButton.disabled = !isValidAmount;
    }

    // Call the function whenever there's a change in the amount input
    document.addEventListener('input', function(event) {
        var targetId = event.target.id;
        if (targetId && targetId.startsWith('withdrawAmount')) {
            var optionId = targetId.replace('withdrawAmount', '');
            var phoneNumberInputId = 'phoneNumber' + optionId;
            var withdrawButtonId = 'withdrawBtn' + optionId;
            var availableBalance = {{ $availableBalance ?? 0 }};
            handleAmountInput(targetId, withdrawButtonId, availableBalance);
        }
    });
</script>

@endsection
