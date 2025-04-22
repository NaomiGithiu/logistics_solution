@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-building me-2"></i>Add Corporate Company
                    </h4>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('corporates.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Company Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" required>
                            <div class="form-text">Enter the official company name</div>
                        </div>

                        <div class="mb-3">
                            <label for="corporate_email" class="form-label fw-bold">Corporate Email</label>
                            <input type="email" name="corporate_email" class="form-control" required>
                            <div class="form-text">Must be a valid company email address</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label fw-bold">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label fw-bold">Contact Number</label>
                                <input type="text" name="contact" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('corporates.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Company
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection