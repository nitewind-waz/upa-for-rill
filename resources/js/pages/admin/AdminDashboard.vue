<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Button from 'primevue/button';

const props = defineProps({
  stats: Object,
  recent_activities: Array
});

const user = usePage().props.auth.user;

const getGreeting = () => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Selamat Pagi';
  if (hour < 15) return 'Selamat Siang';
  if (hour < 18) return 'Selamat Sore';
  return 'Selamat Malam';
};
</script>

<template>
  <AdminLayout>
    <Head title="Dashboard Admin" />

    <div class="space-y-8">
      
      <!-- 1. HERO SECTION (Cleaner & Rapi) -->
      <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
         <!-- Background blob decoration -->
         <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 -mr-10 -mt-10"></div>
         <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 -ml-10 -mb-10"></div>
         
         <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
               <h1 class="text-3xl font-bold text-slate-800">
                  {{ getGreeting() }}, <span class="text-blue-600">{{ user.name }}</span>! 
               </h1>
               <p class="text-slate-500 mt-2 max-w-2xl">
                  Ini adalah panel kontrol utama Anda. Pantau statistik kursus, jadwal EPT, dan kelola konten website dengan mudah dari sini.
               </p>
            </div>
         </div>
      </div>

      <!-- 2. STATS CARDS (Warna-warni: Biru, Hijau, Orange, Merah) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
         
         <!-- Card 1: Kursus (Biru) -->
         <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-blue-500 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
               <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                  <i class="pi pi-book text-xl"></i>
               </div>
               <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-full">AKADEMIK</span>
            </div>
            <div>
               <h3 class="text-3xl font-bold text-slate-800">{{ props.stats.courses }}</h3>
               <p class="text-sm text-slate-500 font-medium mt-1">Total Program Kursus</p>
            </div>
         </div>

         <!-- Card 2: Mahasiswa (Hijau) -->
         <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-emerald-500 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
               <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                  <i class="pi pi-users text-xl"></i>
               </div>
               <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-1 rounded-full">PESERTA</span>
            </div>
            <div>
               <h3 class="text-3xl font-bold text-slate-800">{{ props.stats.mahasiswa }}</h3>
               <p class="text-sm text-slate-500 font-medium mt-1">Mahasiswa Terdaftar</p>
            </div>
         </div>

         <!-- Card 3: Berita (Orange) -->
         <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-orange-500 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
               <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                  <i class="pi pi-megaphone text-xl"></i>
               </div>
               <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-1 rounded-full">PUBLIKASI</span>
            </div>
            <div>
               <h3 class="text-3xl font-bold text-slate-800">{{ props.stats.berita }}</h3>
               <p class="text-sm text-slate-500 font-medium mt-1">Berita & Pengumuman</p>
            </div>
         </div>

         <!-- Card 4: Acara (Merah/Rose) -->
         <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-rose-500 hover:-translate-y-1 transition-transform duration-300">
            <div class="flex justify-between items-start mb-4">
               <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                  <i class="pi pi-calendar text-xl"></i>
               </div>
               <span class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-1 rounded-full">EVENT</span>
            </div>
            <div>
               <h3 class="text-3xl font-bold text-slate-800">{{ props.stats.acara }}</h3>
               <p class="text-sm text-slate-500 font-medium mt-1">Agenda Kegiatan</p>
            </div>
         </div>

      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
         
         <!-- 3. FAST ACTIONS (AKSI CEPAT) -->
         <div class="xl:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
               <i class="pi pi-bolt text-yellow-500"></i> Akses Cepat
            </h3>

            <!-- Group Akademik (Biru) -->
            <div class="bg-blue-50 rounded-2xl border border-blue-100 p-6 relative overflow-hidden">
               <!-- Background Icon -->
               <div class="absolute top-0 right-0 p-4 opacity-10">
                  <i class="pi pi-book text-9xl text-blue-600"></i>
               </div>
               
               <h4 class="font-bold text-blue-800 mb-4 relative z-10">Manajemen Akademik</h4>
               
               <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
                  <Link href="/admin/course" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all group cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <i class="pi pi-plus"></i>
                     </div>
                     <span class="text-sm font-semibold text-slate-700 text-center">Tambah Kursus</span>
                  </Link>
                  <Link href="/admin/jadwal" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all group cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <i class="pi pi-calendar-plus"></i>
                     </div>
                     <span class="text-sm font-semibold text-slate-700 text-center">Kelola Jadwal</span>
                  </Link>
                  <Link href="/admin/ept" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all group cursor-pointer">
                     <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <i class="pi pi-upload"></i>
                     </div>
                     <span class="text-sm font-semibold text-slate-700 text-center">Import Nilai</span>
                  </Link>
               </div>
            </div>

         </div>

         <!-- 4. RECENT ACTIVITY & HELP -->
         <div class="space-y-6">

            <!-- Help Box -->
            <div class="mt-8 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
               <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-xl"></div>
               <h3 class="font-bold text-lg mb-2">Butuh Bantuan?</h3>
               <p class="text-sm text-slate-300 mb-4 opacity-90 leading-relaxed">
                  Download panduan penggunaan dashboard admin untuk memahami semua fitur yang tersedia.
               </p>
               <button class="w-full py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                  <i class="pi pi-download"></i> Unduh Panduan PDF
               </button>
            </div>

         </div>

      </div>

    </div>
  </AdminLayout>
</template>

Dashboard.vue