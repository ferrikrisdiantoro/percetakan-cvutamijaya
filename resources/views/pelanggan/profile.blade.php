@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="flex flex-col w-full mt-4 bg-gray-700 p-6 mx-auto px-4">
   <div class="flex items-center mb-4">
      <img alt="Logo of CV. Utami Jaya" class="mr-4" height="50" src="https://storage.googleapis.com/a1aa/image/orMIbn88dP4KI1WjKM50m4OE43JozeYb8RLb8QVnaF4ykF4JA.jpg" width="100"/>
      <h1 class="text-2xl font-bold text-white">
         DR AKTA PERCETAKAN
      </h1>
   </div>
   <hr class="border-t border-gray-500 mb-4"/>
   <div class="mb-6">
      <h2 class="text-xl font-semibold mb-2 text-white">
         Latar Belakang
      </h2>
      <p class="text-sm text-white">
      CV. Utami Jaya Percetakan merupakan industri percetakan dan perdagangan umum berdiri sejak 1994 di yogyakarta mengerjakan pesanan desain grafis, print laser colour, cetak offset, sablon, hotprint &amp; embossed, finishing, pemotongan kertas, packaging. pelayanan penjilidan berkas panggilan. semua order percetakan dengan harga dan kualitas yang lebih baik. Perusahaan ini berlokasi di Yogyakarta dan melayani berbagai macam kebutuhan cetak, baik untuk keperluan pribadi, bisnis, maupun instansi. (dokter akta spesialis pelayanan Notaris/ PPAT dan BPN RI).
      </p>
   </div>
   <div class="flex justify-between mb-6">
      <div class="w-full md:w-1/2 pr-4">
         <h2 class="text-xl font-semibold mb-2 text-white">
            Visi
         </h2>
         <p class="text-sm text-white">
            Menjadi industri percetakan terkenal di Indonesia yang memprioritaskan kualitas produk dan pelayanan.
         </p>
      </div>
      <div class="w-full md:w-1/2 pl-4">
         <h2 class="text-xl font-semibold mb-2 text-white">
            Misi
         </h2>
         <ol class="list-decimal list-inside text-sm text-white">
            <li>Mengembangkan produk percetakan inovatif yang memenuhi kebutuhan pelanggan dengan kualitas terbaik dan harga yang kompetitif.</li>
            <li>Memanfaatkan teknologi canggih untuk meningkatkan produktivitas dan efisiensi proses produksi sekaligus mengurangi dampak negatif terhadap lingkungan.</li>
            <li>Membangun hubungan yang kuat dan bertahan lama dengan klien, mitra, dan karyawan berdasarkan kepercayaan, saling menghormati, dan saling menguntungkan.</li>
         </ol>
      </div>
   </div>
   <hr class="border-t border-gray-500 mb-4"/>
   <div class="flex justify-between items-center">
      <div>
         <h2 class="text-lg font-semibold mb-2 text-white">
            Hubungi Kami:
         </h2>
         <p class="text-sm text-white">
            Telp: 08122953518
         </p>
         <p class="text-sm text-white">
            Email: dr.aktasutrisno@gmail.com
         </p>
      </div>
      <div>
         <img alt="Map showing the location of CV. Utami Jaya" height="150" src="https://storage.googleapis.com/a1aa/image/BfduKUy4MERqEK55u0PjtIiLKkNHbyn6tMz0B1iHRpHykF4JA.jpg" width="150"/>
      </div>
   </div>
</div>
@endsection
