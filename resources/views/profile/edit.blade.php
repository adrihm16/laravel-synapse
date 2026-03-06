@extends('layouts.store')
@section('title', 'Perfil - Synapse')

@section('content')
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <!-- Profile Information Card -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-md border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-md border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-md border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
