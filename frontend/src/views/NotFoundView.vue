<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">
    <div class="text-center">
      <h1 class="text-9xl font-bold text-gray-800">404</h1>
      <p class="text-2xl font-semibold md:text-3xl text-gray-600 mt-4">Halaman Tidak Ditemukan</p>
      <p class="mt-4 text-gray-500 mb-8">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
      
      <router-link 
        to="/" 
        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300"
      >
        Kembali ke Beranda
      </router-link>
    </div>
  </div>
</template>

<script setup>
// Tidak perlu banyak logika di sini
</script>