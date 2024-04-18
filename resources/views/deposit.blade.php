@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <div class="card-header">
                    <h5 class="card-title text-dark">Deposit</h5>
                </div>
          
                <div class="card-body">
                    {{-- Display success message if it exists --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                    {{-- Display error message if it exists --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <span aria-hidden="true">&times;</span>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('pay') }}" id="paymentForm"> 
                        @csrf
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+251</span>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number" pattern="[0-9]{9}" title="Phone number must be 9 digits">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" min="5" max="5000">
                        </div>
                      
                        <button type="submit" class="btn btn-primary">Deposit</button>
                    </form>
                    
                    {{-- Powered by Chapa --}}
                    <div class="mt-3 text-center text-muted">
                        <p class="mb-1">Powered by <img src="chapa-logo.png" alt="Chapa Logo" width="100"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
