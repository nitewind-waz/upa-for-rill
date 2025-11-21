<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  berita: {
    type: Array,
    default: () => [],
  },
  acara: {
    type: Array,
    default: () => [],
  },
});

const activeTab = ref('berita');
</script>


<template>
  <Head title="Beranda - UPA Bahasa POLBAN" />

  <!-- UBAH WARNA BACKGROUND SELURUH HALAMAN -->
  <div class="min-h-screen bg-[#E8F3FF]"> 
    
    <Layout :nav-bg="'#0A4DAB'"> 
      <!-- nav-bg opsional: jika AppLayout kamu mendukung warna dinamis -->

      <!-- HERO FULL WIDTH BENAR-BENAR 100% -->
      <section
        class="relative w-screen ml-[calc(50%-50vw)] h-[400px] flex flex-col 
               items-center justify-center text-center"
        style="
          background-image: url('/banner-upa.jpeg');
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
        "
      >
        <!-- Overlay gelap -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Isi konten -->
        <div class="relative z-10 max-w-3xl px-4">
          <h1 class="text-white font-extrabold text-5xl drop-shadow-lg">
            UPA Bahasa POLBAN
          </h1>

          <p class="text-gray-200 text-lg mt-4 leading-relaxed">
            UPA Bahasa Polban berkomitmen meningkatkan kemampuan bahasa asing 
            mahasiswa melalui kursus dan pembelajaran interaktif.
          </p>

          <!-- Tombol -->
          <div class="mt-7 flex gap-4 justify-center">
            <button
              class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg transition">
              Lihat Hasil EPT
            </button>

            <button
              class="bg-white hover:bg-gray-200 text-blue-900 font-semibold px-6 py-3 rounded-lg shadow-lg transition">
              Lihat Kursus
            </button>
          </div>
        </div>
      </section>

      <!-- SECTION PROFIL -->
      <section class="w-full py-16 px-6 md:px-20 bg-white shadow-md 
                      w-screen ml-[calc(50%-50vw)]">
        

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
          
          <img 
            src="/profile-upa.jpeg" 
            alt="Profil UPA Bahasa"
            class="rounded-xl shadow-xl w-full object-cover" />

          <div class="text-gray-700 leading-relaxed text-[17px]">
            <h2 class="text-3xl font-bold text-blue-900 mb-10">
              Profil UPA Bahasa
            </h2>
            
            <p>
              Unit Pelaksana Akademik (UPA) Bahasa Politeknik Negeri Bandung 
              merupakan lembaga yang berfokus pada peningkatan kemampuan 
              bahasa asing mahasiswa dan tenaga pendidik. Melalui kursus, 
              tes EPT, dan kegiatan interaktif, kami mencetak generasi 
              yang siap bersaing secara global.
            </p>

            <div class="mt-8 flex gap-10 items-center">
              <div class="text-center">
                <img 
                  src="/ketua.png"
                  class="w-24 h-24 rounded-full object-cover shadow-md mx-auto" />
                <p class="mt-3 font-semibold">Ketua UPA Bahasa</p>
              </div>

              <div class="text-center">
                <img 
                  src="/wakil.png"
                  class="w-24 h-24 rounded-full object-cover shadow-md mx-auto" />
                <p class="mt-3 font-semibold">Wakil Ketua UPA Bahasa</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- SECTION BERITA & ACARA -->
    <section class="w-full py-16 px-6 md:px-20 bg-[#F0F7FF] w-screen ml-[calc(50%-50vw)]">

      <div class="flex justify-center mb-10">
        <div class="inline-flex bg-white rounded-xl shadow border border-blue-200 overflow-hidden">

          <button
            @click="activeTab = 'berita'"
            :class="[
              'px-6 py-2 font-semibold transition duration-200',
              activeTab === 'berita'
                ? 'bg-blue-600 text-white'
                : 'bg-white text-blue-700 hover:bg-blue-50'
            ]"
          >
            Berita
          </button>

          <button
            @click="activeTab = 'acara'"
            :class="[
              'px-6 py-2 font-semibold transition duration-200',
              activeTab === 'acara'
                ? 'bg-blue-600 text-white'
                : 'bg-white text-blue-700 hover:bg-blue-50'
            ]"
          >
            Acara
          </button>

        </div>
      </div>


      <!-- GRID -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- BERITA -->
        <div
          v-if="activeTab === 'berita'"
          v-for="item in berita"
          :key="item.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition"
        >
          <img
            :src="item.gambar ? '/storage/berita/' + item.gambar : '/default.jpg'"
            class="w-full h-48 object-cover"
          />

          <div class="p-5">
            <h3 class="font-semibold text-xl text-blue-900">
              {{ item.judul }}
            </h3>

            <p class="text-gray-600 mt-3 text-sm line-clamp-3">
              {{ item.deskripsi_singkat }}
            </p>

            <p class="text-xs text-gray-500 mt-4">
              {{ item.tanggal }}
            </p>
          </div>
        </div>

        <!-- ACARA -->
        <div
          v-if="activeTab === 'acara'"
          v-for="item in acara"
          :key="item.id"
          class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition"
        >
          <img
            :src="item.gambar ? '/storage/acara/' + item.gambar : '/default.jpg'"
            class="w-full h-48 object-cover"
          />

          <div class="p-5">
            <h3 class="font-semibold text-xl text-blue-900">
              {{ item.judul }}
            </h3>

            <p class="text-gray-600 mt-3 text-sm line-clamp-3">
              {{ item.deskripsi_singkat }}
            </p>

            <p class="text-xs text-gray-500 mt-4">
              {{ item.tanggal_acara }}
            </p>
          </div>
        </div>

      </div>
    </section>


    </Layout>
  </div>
</template>

<style scoped>
</style>
