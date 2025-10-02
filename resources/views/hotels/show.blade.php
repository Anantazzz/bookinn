@extends('layouts.app')    

@section('content')

 <div>
    <div class="container my-4">

  
  <x-header-hotel 
    :id="$hotel->id"
    :gambar="$hotel->gambar"
    :nama_hotel="$hotel->nama_hotel" 
    :kota="$hotel->kota"
    :alamat="$hotel->alamat"
    :rating="$hotel->rating"
    :bintang="$hotel->bintang"
  />
    </div>
@endsection
