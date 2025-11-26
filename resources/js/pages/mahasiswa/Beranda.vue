<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { format } from 'date-fns';

const props = defineProps({
  berita: { type: Array, default: () => [] },
  acara: { type: Array, default: () => [] },
  years: { type: Array, default: () => [] },
  selectedYear: { type: String, default: null },
});

const searchYear = ref(props.selectedYear || new Date().getFullYear());

const getImageUrl = (path, type) => {
  return path ? `/storage/${type}/${path}` : 'https://via.placeholder.com/400x300?text=No+Image';
};

const allItems = computed(() => {
    const mappedBerita = props.berita.map(item => ({
        ...item,
        type: 'berita',
        date: item.tanggal,
    }));
    const mappedAcara = props.acara.map(item => ({
        ...item,
        type: 'acara',
        date: item.tanggal_acara,
    }));

    return [...mappedBerita, ...mappedAcara]
        .sort((a, b) => new Date(b.date) - new Date(a.date));
});

const filterByYear = () => {
    router.get(route('home'), { year: searchYear.value }, {
        preserveState: true,
        replace: true,
    });
};

</script>

<template>
  <Head title="Beranda - UPA Bahasa POLBAN" />

  <Layout>
    <div class="min-h-screen flex flex-col relative">
      
      <section class="relative w-full h-[350px] flex items-center justify-center overflow-hidden z-10 shadow-md">
        <div 
          class="absolute inset-0 w-full h-full bg-cover bg-center z-0 animate-[pulse_15s_ease-in-out_infinite]"
          style="background-image: url('/banner-upa.jpeg');" 
        ></div>
        <div class="absolute inset-0 bg-slate-900/50 z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-50 z-20"></div>

        <div class="relative z-30 container mx-auto px-6 text-center flex flex-col justify-center h-full pt-4">
          <div class="mb-4">
             <span class="py-1.5 px-4 rounded-full bg-white/10 border border-white/30 text-white text-xs font-bold backdrop-blur-md uppercase tracking-widest shadow-lg">
               Official Website
             </span>
          </div>
          <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-3 leading-tight drop-shadow-lg">
            UPA Bahasa <span class="text-blue-200">POLBAN</span>
          </h1>
          <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-6 font-medium leading-relaxed drop-shadow-md">
            Pusat layanan bahasa unggulan untuk mendukung civitas akademika Politeknik Negeri Bandung.
          </p>
          <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
            <a
              href="/jadwal"
              class="inline-block px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-full font-bold text-sm shadow-lg transition-all hover:-translate-y-1"
            >
              Jadwal Tes EPT
            </a>

            <a
              href="/course"
              class="inline-block px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white border border-white/50 rounded-full font-semibold text-sm backdrop-blur-md transition-all hover:-translate-y-1"
            >
              Program Kursus
            </a>
          </div>
        </div>
      </section>

      <section class="relative py-16 z-10"> <div class="container mx-auto px-6">
          <div class="flex flex-col lg:flex-row gap-12 items-stretch">
            
            <div class="w-full lg:w-5/12 relative group">
              <div class="absolute -top-3 -left-3 w-full h-full border-2 border-blue-300/50 rounded-2xl z-0 group-hover:top-0 group-hover:left-0 transition-all duration-500"></div>
              <div class="absolute -bottom-3 -right-3 w-full h-full bg-blue-200 rounded-2xl -z-10"></div>
              
              <div class="relative rounded-2xl overflow-hidden shadow-xl z-10 h-full min-h-[400px]">
                 <img 
                  src="/profile-upa.jpeg" 
                  alt="Gedung UPA Bahasa" 
                  class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-blue-900/60 to-transparent"></div>
                <div class="absolute bottom-6 left-6 text-white">
                   <div class="flex items-center gap-2 mb-1">
                     <i class="pi pi-map-marker text-orange-400"></i>
                     <p class="text-xs font-bold uppercase tracking-wider">Lokasi</p>
                   </div>
                   <p class="font-semibold">Gedung P2T, Lt. 1, POLBAN</p>
                </div>
              </div>
            </div>

            <div class="w-full lg:w-7/12 space-y-8 bg-blue-50/90 backdrop-blur-sm p-8 rounded-3xl border border-blue-100 shadow-sm flex flex-col justify-center">
              <div>
                <div class="flex items-center gap-2 mb-3">
                   <span class="h-1 w-10 bg-blue-600 rounded-full"></span>
                   <span class="text-blue-600 font-bold text-sm uppercase tracking-widest">Tentang Kami</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 mb-4 leading-tight">
                  Mitra Strategis Pengembangan <br/>Bahasa Asing
                </h2>
                <p class="text-slate-600 text-lg leading-relaxed text-justify">
                  UPA Bahasa Politeknik Negeri Bandung hadir sebagai unit pendukung akademik yang memfasilitasi peningkatan kompetensi bahasa. Kami menyediakan layanan tes terstandarisasi (EPT), pelatihan bahasa intensif, dan layanan penerjemahan.
                </p>
              </div>

              <div class="pt-2 mt-auto">
                 <h3 class="text-slate-900 font-bold mb-4 flex items-center gap-2">
                    <i class="pi pi-users text-blue-600 bg-blue-100 p-2 rounded-lg"></i> Pimpinan Unit
                 </h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                       <img src="/ketua.png" class="w-14 h-14 rounded-full object-cover ring-2 ring-blue-100" alt="Ketua" />
                       <div>
                          <p class="font-bold text-slate-800 text-sm">Nama Ketua</p>
                          <p class="text-blue-600 text-[10px] font-bold uppercase">Kepala UPA</p>
                       </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                       <img src="/wakil.png" class="w-14 h-14 rounded-full object-cover ring-2 ring-orange-100" alt="Wakil" />
                       <div>
                          <p class="font-bold text-slate-800 text-sm">Nama Wakil</p>
                          <p class="text-orange-600 text-[10px] font-bold uppercase">Sekretaris</p>
                       </div>
                    </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="pt-4 pb-24 relative z-10"> 
        <div class="container mx-auto px-6">
          
          <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6 border-b border-blue-100 pb-6">
            <div>
               <h2 class="text-3xl font-bold text-slate-900 mb-2">Kabar Terkini</h2>
               <p class="text-slate-500">Update terbaru seputar kegiatan akademik.</p>
            </div>

            <div class="flex items-center gap-2">
                <input 
                    type="number" 
                    v-model="searchYear"
                    placeholder="Contoh: 2025"
                    class="bg-white border border-slate-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <button @click="filterByYear" class="px-6 py-2 rounded-lg text-sm font-bold bg-blue-600 text-white shadow-md hover:bg-blue-700 transition-all">
                    Cari
                </button>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div 
              v-for="item in allItems" 
              :key="`${item.type}-${item.id}`"
              class="group bg-blue-50 rounded-2xl border border-blue-100 overflow-hidden hover:shadow-xl hover:shadow-blue-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-2"
            >
              <div class="relative h-52 overflow-hidden flex-shrink-0">
                <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-colors z-10"></div>
                <img 
                  :src="getImageUrl(item.gambar, item.type)" 
                  class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                />
                <div class="absolute top-3 left-3 z-20 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm border border-blue-100">
                  <span class="text-xs font-bold text-blue-800 flex items-center gap-1">
                    <i class="pi pi-calendar text-blue-600"></i>
                    {{ format(new Date(item.date), 'dd MMMM yyyy') }}
                  </span>
                </div>
                 <div class="absolute top-3 right-3 z-20 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm border border-blue-100">
                  <span class="text-xs font-bold uppercase" :class="item.type === 'berita' ? 'text-sky-800' : 'text-amber-800'">
                    {{ item.type }}
                  </span>
                </div>
              </div>

              <div class="p-6 flex flex-col flex-1">
                <h3 class="text-lg font-bold text-slate-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                  {{ item.judul }}
                </h3>
                
                <div class="flex-1">
                  <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-4">
                    {{ item.deskripsi_singkat }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div v-if="allItems.length === 0" class="text-center py-16 bg-blue-50 rounded-2xl border-2 border-dashed border-blue-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-3">
               <i class="pi pi-inbox text-3xl text-blue-300"></i>
            </div>
            <p class="text-slate-500 font-medium">Belum ada berita atau acara untuk tahun {{ selectedYear }}.</p>
          </div>

        </div>
      </section>

    </div>
  </Layout>
</template>