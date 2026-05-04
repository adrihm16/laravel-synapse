@extends('layouts.store')
@section('title', 'Carrito - Synapse')

@section('content')
  <!--Main-->
  <main class="flex-grow max-w-[95%] mx-auto px-4 md:px-8 py-10 w-full font-sans">
    <x-breadcrumb :items="[['label' => 'Carrito']]" class="mb-8" />
    <div class="mb-12 text-center">
      <h2 class="text-3xl md:text-4xl font-extralight color-[#000000] mt-3 tracking-tight">
        Tu Carrito
      </h2>
    </div>



    <livewire:store-cart />
  </main>
@endsection
