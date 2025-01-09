@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="flex flex-col w-full mt-4 bg-gray-700 p-6 mx-auto px-4">
   <div class="flex items-center mb-4">
      <img alt="Logo of CV. Utami Jaya" class="mr-4 rounded-lg" height="50" src="/images/logo.png" width="100"/>
      <h1 class="text-2xl font-bold text-white">
         DR AKTA PERCETAKAN
      </h1>
   </div>
   <hr class="border-t border-gray-500 mb-4"/>
   <div class="mb-6">
      <h2 class="text-xl font-semibold mb-2 text-white">
         {{$profil->judul_p1}}
      </h2>
      <p class="text-sm text-white">{{$profil->isi_p1}}</p>
   </div>
   <div class="flex justify-between mb-6">
      <div class="w-full md:w-1/2 pr-4">
         <h2 class="text-xl font-semibold mb-2 text-white">{{$profil->visi}}</h2>
         <p class="text-sm text-white">{{$profil->isi_visi}}</p>
      </div>
      <div class="w-full md:w-1/2 pl-4">
         <h2 class="text-xl font-semibold mb-2 text-white">{{$profil->misi}}</h2>
         <p class="text-sm text-white">{{$profil->isi_misi}}</p>
      </div>
   </div>
   <hr class="border-t border-gray-500 mb-4"/>
   <div class="flex justify-between items-center">
      <div>
         <h2 class="text-lg font-semibold mb-2 text-white">{{$profil->kontak}}</h2>
         <p class="text-sm text-white">{{$profil->isi_kontak}}</p>
      </div>
   </div>
</div>
@endsection
