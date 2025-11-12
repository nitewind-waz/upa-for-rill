<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import Layout from '@/layouts/AppLayout.vue'
import Button from 'primevue/button'
import axios from 'axios'

// Props dari Laravel
const props = defineProps({
  jurusan: { type: Array, default: () => [] },
  prodi: { type: Array, default: () => [] },
  kelas: { type: Array, default: () => [] },
  activeTab: { type: String, default: 'Individu' }
})

const activeTab = ref(props.activeTab)

// === State dropdown ===
const selectedJurusan = ref([])
const selectedProdi = ref([])
const selectedKelas = ref([])

const showJurusanDropdown = ref(false)
const showProdiDropdown = ref(false)
const showKelasDropdown = ref(false)

// === State untuk tab Individu ===
const nim = ref('')
const passwrd = ref('')
const eptResults = ref(null)
const errorMessage = ref('')

// === Helper untuk dapatkan nama ===
const getJurusanName = (id) => props.jurusan.find(j => j.id === id)?.nama_jurusan || '-'
const getProdiName = (id) => props.prodi.find(p => p.id === id)?.nama_prodi || '-'
const getKelasName = (id) => props.kelas.find(k => k.id === id)?.nama_kelas || '-'

// === Navigasi antar tab ===
const loadTabData = (tab) => {
  activeTab.value = tab
  if (tab === 'Jurusan') router.get('/hasil/jurusan')
  if (tab === 'Prodi') router.get('/hasil/prodi')
  if (tab === 'Kelas') router.get('/hasil/kelas')
  if (tab === 'Individu') router.get('/hasil')
}

// === Tutup dropdown kalau klik di luar ===
const handleClickOutside = (e) => {
  const dropdowns = document.querySelectorAll('.dropdown-wrapper')
  dropdowns.forEach(d => {
    if (!d.contains(e.target)) {
      if (d.classList.contains('jurusan')) showJurusanDropdown.value = false
      if (d.classList.contains('prodi')) showProdiDropdown.value = false
      if (d.classList.contains('kelas')) showKelasDropdown.value = false
    }
  })
}

// === Fungsi untuk cek hasil EPT individu ===
const handleCheckResult = async () => {
  errorMessage.value = ''
  eptResults.value = null

  // Validasi input
  if (!nim.value && !passwrd.value) {
    errorMessage.value = 'Silakan masukkan NIM dan Password.'
    return
  }
  if (!nim.value) {
    errorMessage.value = 'Silakan masukkan NIM.'
    return
  }
  if (!passwrd.value) {
    errorMessage.value = 'Silakan masukkan Password.'
    return
  }

  try {
    const response = await axios.post('/hasil/check', {
      nim: nim.value,
      password: passwrd.value
    })
    eptResults.value = response.data.results
  } catch (error) {
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Terjadi kesalahan. Silakan coba lagi.'
    }
  }
}


onMounted(() => document.addEventListener('click', handleClickOutside))
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside))
</script>

<template>
  <div class="bg-white min-h-screen">
    <Layout :show-hero="true">
      <section class="py-20 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-blue-800 mb-4">
          Hasil EPT
        </h1>
        <p class="text-lg text-blue-900 mb-10">
          Lihat hasil English Proficiency Test (EPT) kamu di sini.
        </p>
      </section>

      <!-- Tabs -->
      <section class="container mx-auto pb-12 px-6 max-w-2xl">
        <div class="flex justify-center bg-gray-200 rounded-lg p-1.5 gap-1">
          <Button
            v-for="tab in ['Jurusan','Prodi','Kelas','Individu']"
            :key="tab"
            :label="tab"
            @click="() => loadTabData(tab)"
            unstyled
            :class="[
              'w-full font-semibold transition-all duration-200 py-2',
              activeTab === tab
                ? 'bg-orange-500 text-white shadow-md'
                : 'bg-blue-600 text-white hover:bg-blue-500'
            ]"
            style="border-radius:6px; border:none;"
          />
        </div>
      </section>

      <!-- Konten -->
      <section class="container mx-auto px-6 max-w-4xl text-center py-20">
        
        <!-- ==================== TAB JURUSAN ==================== -->
        <div v-if="activeTab === 'Jurusan'">
          <h2 class="text-2xl font-bold text-blue-800 mb-3">Pilih Jurusan</h2>
          <div class="flex justify-center">
            <div class="relative dropdown-wrapper jurusan w-1/2 text-left">
              <button
                type="button"
                @click.stop="showJurusanDropdown = !showJurusanDropdown"
                class="border border-gray-400 rounded-lg px-4 py-2 text-gray-700 w-full text-left focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center transition-all duration-200"
              >
                <span v-if="selectedJurusan.length">
                  {{ selectedJurusan.map(id => getJurusanName(id)).join(', ') }}
                </span>
                <span v-else class="text-gray-400">Pilih Jurusan</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="w-5 h-5 ml-2 text-gray-500 transition-transform duration-300 ease-in-out"
                     :class="{ 'rotate-180': showJurusanDropdown }">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div v-if="showJurusanDropdown"
                   class="absolute z-10 mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                <div v-for="j in props.jurusan" :key="j.id"
                     class="px-4 py-2 flex items-center hover:bg-blue-100 text-black">
                  <input type="checkbox" :id="`jurusan-${j.id}`" :value="j.id"
                         v-model="selectedJurusan" class="mr-2" />
                  <label :for="`jurusan-${j.id}`" class="cursor-pointer">{{ j.nama_jurusan }}</label>
                </div>
              </div>
            </div>
          </div>

          <div v-if="selectedJurusan.length" class="mt-4 text-gray-800">
            Jurusan terpilih:
            <span class="font-semibold text-blue-800">
              {{ selectedJurusan.map(id => getJurusanName(id)).join(', ') }}
            </span>
          </div>
        </div>

        <!-- ==================== TAB PRODI ==================== -->
        <div v-else-if="activeTab === 'Prodi'">
          <h2 class="text-2xl font-bold text-blue-800 mb-3">Pilih Prodi</h2>
          <div class="flex justify-center">
            <div class="relative dropdown-wrapper prodi w-1/2 text-left">
              <button
                type="button"
                @click.stop="showProdiDropdown = !showProdiDropdown"
                class="border border-gray-400 rounded-lg px-4 py-2 text-gray-700 w-full text-left focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center transition-all duration-200"
              >
                <span v-if="selectedProdi.length">
                  {{ selectedProdi.map(id => getProdiName(id)).join(', ') }}
                </span>
                <span v-else class="text-gray-400">Pilih Prodi</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="w-5 h-5 ml-2 text-gray-500 transition-transform duration-300 ease-in-out"
                     :class="{ 'rotate-180': showProdiDropdown }">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div v-if="showProdiDropdown"
                   class="absolute z-10 mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                <div v-for="p in props.prodi" :key="p.id"
                     class="px-4 py-2 flex items-center hover:bg-blue-100 text-black">
                  <input type="checkbox" :id="`prodi-${p.id}`" :value="p.id"
                         v-model="selectedProdi" class="mr-2" />
                  <label :for="`prodi-${p.id}`" class="cursor-pointer">{{ p.nama_prodi }}</label>
                </div>
              </div>
            </div>
          </div>

          <div v-if="selectedProdi.length" class="mt-4 text-gray-800">
            Prodi terpilih:
            <span class="font-semibold text-blue-800">
              {{ selectedProdi.map(id => getProdiName(id)).join(', ') }}
            </span>
          </div>
        </div>

        <!-- ==================== TAB KELAS ==================== -->
        <div v-else-if="activeTab === 'Kelas'">
          <h2 class="text-2xl font-bold text-blue-800 mb-3">Pilih Kelas</h2>
          <div class="flex justify-center">
            <div class="relative dropdown-wrapper kelas w-1/2 text-left">
              <button
                type="button"
                @click.stop="showKelasDropdown = !showKelasDropdown"
                class="border border-gray-400 rounded-lg px-4 py-2 text-gray-700 w-full text-left focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center transition-all duration-200"
              >
                <span v-if="selectedKelas.length">
                  {{ selectedKelas.map(id => getKelasName(id)).join(', ') }}
                </span>
                <span v-else class="text-gray-400">Pilih Kelas</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="w-5 h-5 ml-2 text-gray-500 transition-transform duration-300 ease-in-out"
                     :class="{ 'rotate-180': showKelasDropdown }">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </button>

              <div v-if="showKelasDropdown"
                   class="absolute z-10 mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                <div v-for="k in props.kelas" :key="k.id"
                     class="px-4 py-2 flex items-center hover:bg-blue-100 text-black">
                  <input type="checkbox" :id="`kelas-${k.id}`" :value="k.id"
                         v-model="selectedKelas" class="mr-2" />
                  <label :for="`kelas-${k.id}`" class="cursor-pointer">{{ k.nama_kelas }}</label>
                </div>
              </div>
            </div>
          </div>

          <div v-if="selectedKelas.length" class="mt-4 text-gray-800">
            Kelas terpilih:
            <span class="font-semibold text-blue-800">
              {{ selectedKelas.map(id => getKelasName(id)).join(', ') }}
            </span>
          </div>
        </div>

        <!-- ==================== TAB INDIVIDU ==================== -->
        <div v-else-if="activeTab === 'Individu'">
          <h2 class="text-2xl font-bold text-blue-800 mb-6">Cek Hasil EPT Individu</h2>

          <div class="max-w-md mx-auto text-left bg-white border border-gray-300 rounded-xl shadow-lg p-6">
            <div class="mb-4">
              <label for="nim" class="block text-gray-700 font-semibold mb-2">NIM</label>
              <input
                id="nim"
                v-model="nim"
                type="text"
                placeholder="Masukkan NIM"
                class="w-full border border-gray-300 text-gray-800 placeholder-gray-400 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div class="mb-6">
              <label for="passwrd" class="block text-gray-700 font-semibold mb-2">Password</label>
              <input
                id="passwrd"
                v-model="passwrd"
                type="password"
                placeholder="Masukkan Password"
                class="w-full border border-gray-300 text-gray-800 placeholder-gray-400 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <Button
              label="Lihat Hasil"
              class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded-lg transition"
              @click="handleCheckResult"
            />

            <p v-if="errorMessage" class="text-red-600 mt-3 text-center">{{ errorMessage }}</p>
          </div>

          <!-- Tabel Hasil -->
          <!-- Hasil EPT Individu -->
          <div v-if="eptResults" class="mt-10 flex flex-wrap justify-center gap-6">
            <div v-for="(r, index) in eptResults" :key="index"
                class="bg-gradient-to-br from-white to-blue-50 border border-gray-200 rounded-3xl shadow-lg p-6 w-72 flex flex-col justify-between transition-transform hover:scale-105">
              
              <!-- Tahun -->
              <div class="text-center mb-4">
                <p class="text-gray-400 font-medium uppercase text-sm">Tahun</p>
                <p class="text-blue-700 font-bold text-2xl">{{ r.tahun }}</p>
              </div>

              <!-- Listening -->
              <div class="flex justify-between items-center mb-2">
                <p class="text-gray-600 font-medium">Listening</p>
                <p class="text-indigo-600 font-semibold">{{ r.listening }}</p>
              </div>

              <!-- Structure -->
              <div class="flex justify-between items-center mb-2">
                <p class="text-gray-600 font-medium">Structure</p>
                <p class="text-green-600 font-semibold">{{ r.structure }}</p>
              </div>

              <!-- Reading -->
              <div class="flex justify-between items-center mb-2">
                <p class="text-gray-600 font-medium">Reading</p>
                <p class="text-orange-600 font-semibold">{{ r.reading }}</p>
              </div>

              <!-- Total -->
              <div class="flex justify-between items-center mt-4 pt-2 border-t border-gray-200">
                <p class="text-gray-700 font-semibold uppercase">Total</p>
                <p class="text-blue-800 font-bold text-xl">{{ r.total_score }}</p>
              </div>
            </div>
          </div>

        </div>
      </section>
    </Layout>
  </div>
</template>
