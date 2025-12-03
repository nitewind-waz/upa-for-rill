<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  berita: { type: Array, default: () => [] },
  acara: { type: Array, default: () => [] },
});

const activeTab = ref('berita');

const getImageUrl = (path, type) => {
  return path ? `/storage/${type}/${path}` : 'https://via.placeholder.com/400x300?text=No+Image';
};
</script>

<template>
  <Head title="Beranda - UPA Bahasa POLBAN" />

  <Layout>
    <div class="min-h-screen flex flex-col relative">
      
      <!-- Hero Section -->
      <section class="relative w-full h-[350px] flex items-center justify-center overflow-hidden z-10 shadow-md">
        <div 
          class="absolute inset-0 w-full h-full bg-cover bg-center z-0 animate-[pulse_15s_ease-in-out_infinite]"
          style="background-image: url('/banner-upa.jpeg');" 
        ></div>
        <div class="absolute inset-0 bg-slate-900/50 z-10"></div>

        <div class="relative z-30 container mx-auto px-6 text-center flex flex-col justify-center h-full pt-4">
          <div class="mb-4">
             <span class="py-1.5 px-4 rounded-full bg-white/10 border border-white/30 text-white text-xs font-bold backdrop-blur-md uppercase tracking-widest shadow-lg">
               Official Website
             </span>
          </div>
          <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-3 leading-tight drop-shadow-lg">
            <span class="text-blue-200">Selamat Datang!</span>
          </h1>
          <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-6 font-medium leading-relaxed drop-shadow-md">
            Pusat layanan bahasa unggulan untuk mendukung civitas akademika Politeknik Negeri Bandung.
          </p>
          <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <Link
              href="/jadwal"
              class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold text-sm shadow-lg transition-all hover:-translate-y-1"
            >
              Jadwal Tes EPT
            </Link>

            <Link
              href="/kursus"
              class="inline-block px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/50 rounded-full font-semibold text-sm backdrop-blur-md transition-all hover:-translate-y-1"
            >
              Program Kursus
            </Link>
          </div>
        </div>
      </section>

      <!-- Profile Section -->
      <section class="relative py-16 z-10">
        <div class="container mx-auto px-6">
          <div class="flex flex-col lg:flex-row gap-12 items-stretch">
            
            <!-- Image Section - Mobile Optimized -->
            <div class="w-full lg:w-5/12 relative group">
              <div class="absolute -top-3 -left-3 w-full h-full border-2 border-blue-300/50 rounded-2xl z-0 group-hover:top-0 group-hover:left-0 transition-all duration-500"></div>
              <div class="absolute -bottom-3 -right-3 w-full h-full bg-blue-200 rounded-2xl -z-10"></div>
              
              <div class="relative rounded-2xl overflow-hidden shadow-xl z-10 h-full min-h-[300px] md:min-h-[400px]">
                 <img 
                  src="/profile-upa.jpeg" 
                  alt="Gedung UPA Bahasa" 
                  class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 to-transparent"></div>
                <div class="absolute bottom-4 md:bottom-6 left-4 md:left-6 text-white">
                   <div class="flex items-center gap-2 mb-1">
                     <i class="pi pi-map-marker text-orange-400"></i>
                     <p class="text-xs font-bold uppercase tracking-wider">Lokasi</p>
                   </div>
                   <p class="font-semibold text-sm md:text-base">Gedung P2T, Lt. 1, POLBAN</p>
                </div>
              </div>
            </div>

            <!-- Content Section - Mobile Optimized -->
            <div class="w-full lg:w-7/12 space-y-6 md:space-y-8 bg-blue-50/90 backdrop-blur-sm p-6 md:p-8 rounded-3xl border border-blue-100 shadow-sm flex flex-col justify-center">
              <div>
                <div class="flex items-center gap-2 mb-3">
                   <span class="h-1 w-10 bg-blue-600 rounded-full"></span>
                   <span class="text-blue-600 font-bold text-xs md:text-sm uppercase tracking-widest">Tentang Kami</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-3 md:mb-4 leading-tight">
                  Mitra Strategis Pengembangan <br class="hidden md:block"/>Bahasa Asing
                </h2>
                <p class="text-slate-600 text-base md:text-lg leading-relaxed text-justify">
                  UPA Bahasa Politeknik Negeri Bandung hadir sebagai unit pendukung akademik yang memfasilitasi peningkatan kompetensi bahasa. Kami menyediakan layanan tes terstandarisasi (EPT), pelatihan bahasa intensif, dan layanan penerjemahan.
                </p>
              </div>

              <!-- Leadership Section - Mobile Optimized -->
              <div class="pt-2 mt-auto">
                 <h3 class="text-slate-900 font-bold mb-4 flex items-center gap-2 text-sm md:text-base">
                    <i class="pi pi-users text-blue-600 bg-blue-100 p-2 rounded-lg"></i> Pimpinan Unit
                 </h3>
                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                    <div class="bg-white p-3 md:p-4 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all flex items-center gap-3 md:gap-4">
                       <img src="/ketua.png" class="w-12 h-12 md:w-14 md:h-14 rounded-full object-cover ring-2 ring-blue-100 flex-shrink-0" alt="Ketua" />
                       <div class="min-w-0">
                          <p class="font-bold text-slate-800 text-xs md:text-sm truncate">Nama Ketua</p>
                          <p class="text-blue-600 text-[9px] md:text-[10px] font-bold uppercase">Kepala UPA</p>
                       </div>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all flex items-center gap-3 md:gap-4">
                       <img src="/wakil.png" class="w-12 h-12 md:w-14 md:h-14 rounded-full object-cover ring-2 ring-orange-100 flex-shrink-0" alt="Wakil" />
                       <div class="min-w-0">
                          <p class="font-bold text-slate-800 text-xs md:text-sm truncate">Nama Wakil</p>
                          <p class="text-orange-600 text-[9px] md:text-[10px] font-bold uppercase">Sekretaris</p>
                       </div>
                    </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- News & Events Section -->
      <section class="pt-4 pb-24 relative z-10"> 
        <div class="container mx-auto px-6">
          
          <!-- Header with Tab - Centered on Mobile -->
          <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-8 gap-6 border-b border-blue-100 pb-6">
            <div class="text-center md:text-left">
               <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">Kabar Terkini</h2>
               <p class="text-slate-500 text-sm md:text-base">Update terbaru seputar kegiatan akademik.</p>
            </div>

            <!-- Tab Buttons - Centered -->
            <div class="bg-blue-50 p-1 rounded-xl shadow-sm border border-blue-100 inline-flex">
              <button
                @click="activeTab = 'berita'"
                class="px-4 md:px-6 py-2 rounded-lg text-xs md:text-sm font-bold transition-all duration-300"
                :class="activeTab === 'berita' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-blue-100 hover:text-blue-600'"
              >
                Berita
              </button>
              <button
                @click="activeTab = 'acara'"
                class="px-4 md:px-6 py-2 rounded-lg text-xs md:text-sm font-bold transition-all duration-300"
                :class="activeTab === 'acara' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-blue-100 hover:text-blue-600'"
              >
                Acara
              </button>
            </div>
          </div>

          <!-- Cards Grid - Horizontal Scroll on Mobile -->
          <div class="overflow-x-auto pb-4 -mx-6 px-6 md:overflow-visible">
            <div class="flex md:grid md:grid-cols-3 gap-4 md:gap-8">
              <div 
                v-for="item in (activeTab === 'berita' ? berita : acara)" 
                :key="item.id"
                class="w-[calc(33.333%-10.67px)] min-w-[calc(33.333%-10.67px)] md:w-auto md:min-w-0 flex-shrink-0 group bg-blue-50 rounded-2xl border border-blue-100 overflow-hidden hover:shadow-xl hover:shadow-blue-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-2"
              >
                <div class="relative h-40 md:h-52 overflow-hidden flex-shrink-0">
                  <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-colors z-10"></div>
                  <img 
                    :src="getImageUrl(item.gambar, activeTab)" 
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                  />
                  <div class="absolute top-2 left-2 md:top-3 md:left-3 z-20 bg-white/90 backdrop-blur-sm px-2 py-0.5 md:px-3 md:py-1 rounded-lg shadow-sm border border-blue-100">
                    <span class="text-[10px] md:text-xs font-bold text-blue-800 flex items-center gap-1">
                      <i class="pi pi-calendar text-blue-600 text-[10px] md:text-xs"></i>
                      <span class="hidden sm:inline">{{ activeTab === 'berita' ? item.tanggal : item.tanggal_acara }}</span>
                      <span class="sm:hidden">{{ (activeTab === 'berita' ? item.tanggal : item.tanggal_acara).slice(0, 10) }}</span>
                    </span>
                  </div>
                </div>

                <div class="p-4 md:p-6 flex flex-col flex-1">
                  <h3 class="text-sm md:text-lg font-bold text-slate-800 mb-2 md:mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                    {{ item.judul }}
                  </h3>
                  
                  <div class="flex-1">
                    <p class="text-slate-500 text-xs md:text-sm leading-relaxed line-clamp-3 mb-2 md:mb-4">
                      {{ item.deskripsi_singkat }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="(activeTab === 'berita' ? berita : acara).length === 0" class="text-center py-16 bg-blue-50 rounded-2xl border-2 border-dashed border-blue-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-3">
               <i class="pi pi-inbox text-3xl text-blue-300"></i>
            </div>
            <p class="text-slate-500 font-medium">Belum ada data yang ditampilkan.</p>
          </div>

        </div>
      </section>

    </div>
  </Layout>
</template>

<style scoped>
/* Custom scrollbar untuk horizontal scroll di mobile */
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>