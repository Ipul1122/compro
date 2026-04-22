<script setup>
import AboutSection from '../section/AboutSection.vue'
</script>

<template>
  <div class="pt-24 pb-12"> 
    
    <AboutSection :showButton="false" />
    
    <section class="max-w-7xl mx-auto px-6 py-12">
        <div class="bg-slate-50 rounded-3xl p-8 md:p-12 border border-slate-100 shadow-sm">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6">Visi & Misi Perusahaan</h1>
            
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-brand mb-2">Visi Kami</h2>
                    <p class="text-slate-600 leading-relaxed">
                        Menjadi perusahaan terdepan berskala internasional yang memberikan solusi komprehensif 
                        dan inovatif, serta menciptakan nilai tambah bagi seluruh pemangku kepentingan.
                    </p>
                </div>
                
                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-xl font-bold text-brand mb-2">Misi Kami</h2>
                    <ul class="list-disc list-inside text-slate-600 leading-relaxed space-y-2">
                        <li>Memberikan layanan dan produk berkualitas tinggi yang melebihi ekspektasi pelanggan.</li>
                        <li>Mengembangkan inovasi berkelanjutan dalam setiap aspek operasional perusahaan.</li>
                        <li>Membangun kemitraan strategis yang saling menguntungkan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

  </div>
</template>