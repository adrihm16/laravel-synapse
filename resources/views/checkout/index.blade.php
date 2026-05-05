@extends('layouts.store')
@section('title', 'Checkout - Synapse')

@section('content')
  <main class="flex-grow max-w-[95%] mx-auto px-4 md:px-8 py-10 w-full font-sans">
    <x-breadcrumb :items="[['label' => 'Carrito', 'url' => route('cart.index')], ['label' => 'Checkout']]" class="mb-8" />
    <livewire:checkout-wizard />
  </main>
@endsection
