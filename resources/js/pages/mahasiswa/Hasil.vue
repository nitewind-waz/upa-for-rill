<script setup>
import { ref, watch } from 'vue'
import Layout from '@/layouts/AppLayout.vue'
import Button from 'primevue/button'
import Chart from 'primevue/chart'
import MultiSelect from 'primevue/multiselect'
import axios from 'axios'
import ProgressSpinner from 'primevue/progressspinner';

// Props dari Laravel
const props = defineProps({
  jurusan: { type: Array, default: () => [] },
  prodi: { type: Array, default: () => [] },
  kelas: { type: Array, default: () => [] },
  activeTab: { type: String, default: 'Individu' }
})

const activeTab = ref(props.activeTab)
const isStatsLoading = ref(false) // Renamed
const statsErrorMessage = ref('') // Renamed

// === State dropdown ===
const selectedJurusan = ref([])
const selectedProdi = ref([])
const selectedKelas = ref([])

// === State untuk tab Individu ===
const nim = ref('')
const passwrd = ref('')
const eptResults = ref(null)
const isIndividuLoading = ref(false) // New
const individuErrorMessage = ref('') // New
const chartData = ref(null)

// === Ambil data statistik untuk grafik ===
const getStats = async (level) => {
  chartData.value = null
  statsErrorMessage.value = '' // Use statsErrorMessage
  let ids = []
  if (level === 'jurusan') ids = selectedJurusan.value
  else if (level === 'prodi') ids = selectedProdi.value
  else if (level === 'kelas') ids = selectedKelas.value

  if (ids.length === 0) {
    return
  }
  
  isStatsLoading.value = true // Use isStatsLoading
  try {
    const response = await axios.post('/hasil/stats', { level, ids })
    chartData.value = response.data
  } catch (error) {
    statsErrorMessage.value = error.response?.data?.message || 'Terjadi kesalahan saat mengambil data statistik.' // Use statsErrorMessage
  } finally {
    isStatsLoading.value = false // Use isStatsLoading
  }
}

// Watchers untuk auto-update
watch(selectedJurusan, () => getStats('jurusan'))
watch(selectedProdi, () => getStats('prodi'))
watch(selectedKelas, () => getStats('kelas'))


// === Navigasi antar tab ===
const loadTabData = (tab) => {
  activeTab.value = tab
  // Reset state saat pindah tab
  chartData.value = null
  statsErrorMessage.value = '' // Reset statsErrorMessage
  isStatsLoading.value = false // Reset statsLoading
  individuErrorMessage.value = '' // Reset individuErrorMessage
  isIndividuLoading.value = false // Reset individuLoading
  selectedJurusan.value = []
  selectedProdi.value = []
  selectedKelas.value = []
}

// === Fungsi untuk cek hasil EPT individu ===
const handleCheckResult = async () => {
  individuErrorMessage.value = '' // Use individuErrorMessage
  eptResults.value = null

  if (!nim.value || !passwrd.value) {
    individuErrorMessage.value = 'Silakan masukkan NIM dan Password.' // Use individuErrorMessage
    return
  }
  
  isIndividuLoading.value = true // Use isIndividuLoading
  try {
    const response = await axios.post('/hasil/check', {
      nim: nim.value,
      password: passwrd.value
    })
    eptResults.value = response.data.results
  } catch (error) {
    individuErrorMessage.value = error.response?.data?.message || 'Terjadi kesalahan. Silakan coba lagi.' // Use individuErrorMessage
  } finally {
    isIndividuLoading.value = false // Use isIndividuLoading
  }
}
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
      <section class="container mx-auto px-6 max-w-6xl text-center py-10">
        
        <!-- ==================== TAB JURUSAN ==================== -->
        <div v-if="activeTab === 'Jurusan'">
          <h2 class="text-2xl font-bold text-blue-800 mb-4">Pilih Jurusan</h2>
          <div class="flex justify-center items-center gap-2 w-full max-w-xl mx-auto">
            <MultiSelect
              v-model="selectedJurusan"
              :options="props.jurusan"
              optionLabel="nama_jurusan"
              optionValue="id"
              placeholder="Pilih satu atau lebih jurusan"
              class="w-full"
              :showSelectAll="false"
            />
            <Button
              v-if="selectedJurusan.length"
              icon="pi pi-times"
              severity="danger"
              text
              rounded
              @click="selectedJurusan = []"
            />
          </div>
          <!-- Loading & Error for Stats Tabs -->
          <div v-if="isStatsLoading" class="mt-10">
              <ProgressSpinner />
              <p class="text-blue-600 mt-2">Memuat data...</p>
          </div>
          <p v-if="statsErrorMessage" class="text-red-600 mt-4">{{ statsErrorMessage }}</p>
        </div>

        <!-- ==================== TAB PRODI ==================== -->
        <div v-else-if="activeTab === 'Prodi'">
          <h2 class="text-2xl font-bold text-blue-800 mb-4">Pilih Prodi</h2>
          <div class="flex justify-center items-center gap-2 w-full max-w-xl mx-auto">
            <MultiSelect
              v-model="selectedProdi"
              :options="props.prodi"
              optionLabel="nama_prodi"
              optionValue="id"
              placeholder="Pilih satu atau lebih prodi"
              class="w-full"
              :showSelectAll="false"
            />
            <Button
              v-if="selectedProdi.length"
              icon="pi pi-times"
              severity="danger"
              text
              rounded
              @click="selectedProdi = []"
            />
          </div>
          <!-- Loading & Error for Stats Tabs -->
          <div v-if="isStatsLoading" class="mt-10">
              <ProgressSpinner />
              <p class="text-blue-600 mt-2">Memuat data...</p>
          </div>
          <p v-if="statsErrorMessage" class="text-red-600 mt-4">{{ statsErrorMessage }}</p>
        </div>

        <!-- ==================== TAB KELAS ==================== -->
        <div v-else-if="activeTab === 'Kelas'">
          <h2 class="text-2xl font-bold text-blue-800 mb-4">Pilih Kelas</h2>
          <div class="flex justify-center items-center gap-2 w-full max-w-xl mx-auto">
            <MultiSelect
              v-model="selectedKelas"
              :options="props.kelas"
              optionLabel="nama_kelas"
              optionValue="id"
              placeholder="Pilih satu atau lebih kelas"
              class="w-full"
              :showSelectAll="false"
            />
            <Button
              v-if="selectedKelas.length"
              icon="pi pi-times"
              severity="danger"
              text
              rounded
              @click="selectedKelas = []"
            />
          </div>
          <!-- Loading & Error for Stats Tabs -->
          <div v-if="isStatsLoading" class="mt-10">
              <ProgressSpinner />
              <p class="text-blue-600 mt-2">Memuat data...</p>
          </div>
          <p v-if="statsErrorMessage" class="text-red-600 mt-4">{{ statsErrorMessage }}</p>
        </div>

        <!-- Loading & Error (Moved to specific tabs) -->

        <!-- Hasil Chart & Statistik -->
        <div v-if="chartData && !isStatsLoading" class="mt-12">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Kolom untuk Statistik Numerik -->
            <div class="lg:col-span-1">
                <h3 class="text-xl font-bold text-blue-800 mb-4 text-left">Statistik Total Skor</h3>

                <!-- Layout for 1-2 items (Bigger) -->
                <div v-if="chartData.chart.datasets.length <= 2" class="space-y-4">
                    <div v-for="(dataset, index) in chartData.chart.datasets" :key="index" class="p-5 border rounded-xl shadow-lg bg-gray-50 text-left">
                        <h4 class="font-bold text-lg text-blue-700 mb-3">{{ dataset.label }}</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <p class="font-medium text-gray-600">Skor Tertinggi:</p>
                                <p class="font-semibold text-blue-600">{{ dataset.data[0] }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="font-medium text-gray-600">Skor Terendah:</p>
                                <p class="font-semibold text-red-600">{{ dataset.data[1] }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="font-medium text-gray-600">Rata-rata Skor:</p>
                                <p class="font-semibold text-green-600">{{ dataset.data[2] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layout for >2 items (Compact & Horizontal) -->
                <div v-else :class="[
                    'grid gap-3',
                    'grid-cols-1 sm:grid-cols-2' // Default to 1 column on small, 2 on sm and up
                ]">
                    <div v-for="(dataset, index) in chartData.chart.datasets" :key="index" class="p-3 border rounded-lg shadow-md bg-gray-50 text-left">
                        <h4 class="font-semibold text-base text-blue-700 mb-2">{{ dataset.label }}</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-600">Tertinggi:</p>
                                <p class="font-bold text-blue-600">{{ dataset.data[0] }}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-600">Terendah:</p>
                                <p class="font-bold text-red-600">{{ dataset.data[1] }}</p>
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-600">Rata-rata:</p>
                                <p class="font-bold text-green-600">{{ dataset.data[2] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom untuk Grafik -->
            <div class="lg:col-span-2">
              <div class="p-6 border rounded-xl shadow-lg bg-gray-50 h-full">
                <h3 class="text-xl font-bold text-blue-800 mb-5 text-left">Grafik Perbandingan Total Skor</h3>
                <Chart type="bar" :data="chartData.chart" />
              </div>
            </div>

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
              :disabled="isIndividuLoading"
            />
          </div>

          <!-- Loading & Error for Individu Tab -->
          <div v-if="isIndividuLoading" class="mt-10">
              <ProgressSpinner />
              <p class="text-blue-600 mt-2">Memuat data...</p>
          </div>
          <p v-if="individuErrorMessage" class="text-red-600 mt-4">{{ individuErrorMessage }}</p>

          <!-- Hasil EPT Individu -->
          <div v-if="eptResults" class="mt-10 flex flex-wrap justify-center gap-6">
            <div v-for="(r, index) in eptResults" :key="index"
                class="bg-gradient-to-br from-white to-blue-50 border border-gray-200 rounded-3xl shadow-lg p-6 w-72 flex flex-col justify-between transition-transform hover:scale-105">
              
              <div class="text-center mb-4">
                <p class="text-gray-400 font-medium uppercase text-sm">Tahun</p>
                <p class="text-blue-700 font-bold text-2xl">{{ r.tahun }}</p>
              </div>

              <div class="flex justify-between items-center py-2 border-b">
                <p class="text-gray-600 font-medium">Listening</p>
                <p class="text-indigo-600 font-semibold">{{ r.listening }}</p>
              </div>

              <div class="flex justify-between items-center py-2 border-b">
                <p class="text-gray-600 font-medium">Structure</p>
                <p class="text-green-600 font-semibold">{{ r.structure }}</p>
              </div>

              <div class="flex justify-between items-center py-2">
                <p class="text-gray-600 font-medium">Reading</p>
                <p class="text-orange-600 font-semibold">{{ r.reading }}</p>
              </div>

              <div class="flex justify-between items-center mt-4 pt-4 border-t-2 border-blue-200">
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