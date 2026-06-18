@extends('layouts.myapp')
@section('title', 'Profile')
@section('content')
<div class="container py-3">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Profile</h5>
        </div>

        <div class="card-body">

            {{-- Update Profile --}}
            <div class="mb-4">
                <h6 class="text-muted">Update Profile Information</h6>
                @include('profile.partials.update-profile-information-form')
            </div>

            <hr>

            {{-- Update Password --}}
            <div class="mb-4">
                <h6 class="text-muted">Change Password</h6>
                @include('profile.partials.update-password-form')
            </div>

            <hr>

            {{-- Delete Account --}}
            <div class="mb-4">
                <h6 class="text-danger">Danger Zone</h6>
                @include('profile.partials.delete-user-form')
            </div>

        </div>

    </div>

</div>

@endsection