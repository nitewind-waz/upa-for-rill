<script setup>
import { ref, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import Layout from '@/layouts/AppLayout.vue'
import Button from 'primevue/button'
import Chart from 'primevue/chart'
import MultiSelect from 'primevue/multiselect'
import ProgressSpinner from 'primevue/progressspinner'
import { debounce } from 'lodash';

// Props dari Laravel
const props = defineProps({
  jurusan: { type: Array, default: () => [] },
  prodi: { type: Array, default: () => [] },
  kelas: { type: Array, default: () => [] },
  activeTab: { type: String, default: 'Individu' },
  eptResults: { type: Array, default: null },
  mahasiswa: { type: Object, default: null },
  stats: { type: Object, default: null },
  errors: { type: Object, default: () => ({}) },
  input: { type: Object, default: () => ({}) }
})

const activeTab = ref(props.activeTab)

// === State Individu ===
const form = useForm({
  nim: props.input?.nim || '',
  password: ''
})

// === Dropdown State ===
const selectedJurusan = ref(props.input?.level === 'jurusan' ? props.input.ids : [])
const selectedProdi = ref(props.input?.level === 'prodi' ? props.input.ids : [])
const selectedKelas = ref(props.input?.level === 'kelas' ? props.input.ids : [])

// === Fungsi Individu ===
const handleCheckResult = () => {
  form.post('/hasil/check', {
    preserveState: (page) => Object.keys(page.props.errors).length > 0,
    preserveScroll: true,
  })
}

// === Ambil Statistik ===
const getStats = debounce((level, ids) => {
  if (ids.length === 0) {
    router.get('/hasil', { tab: level.charAt(0).toUpperCase() + level.slice(1) }, { preserveState: true, preserveScroll: true, replace: true });
    return;
  }
  
  router.get('/hasil/stats', { level, ids }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}, 300);

// Watchers auto update
watch(selectedJurusan, (newVal) => getStats('jurusan', newVal))
watch(selectedProdi, (newVal) => getStats('prodi', newVal))
watch(selectedKelas, (newVal) => getStats('kelas', newVal))

// === Navigasi Tab ===
const loadTabData = (tab) => {
  activeTab.value = tab
  router.visit(`/hasil?tab=${tab}`, { preserveState: true, preserveScroll: true, replace: true })
}

// === NEW: Bar Chart Options biar tinggi dan full ===
const barChartOptions = {
  maintainAspectRatio: false,
  responsive: true,
  plugins: {
    legend: {
      position: 'bottom',
      labels: { color: '#333', padding: 20 }
    }
  },
  scales: {
    x: {
      ticks: { color: '#333' },
      grid: { display: false }
    },
    y: {
      ticks: { color: '#333' },
      beginAtZero: true
    }
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
          <div class="flex flex-col items-center gap-2 w-full max-w-xl mx-auto">
            <MultiSelect
              v-model="selectedJurusan"
              :options="props.jurusan"
              optionLabel="nama_jurusan"
              optionValue="id"
              placeholder="Pilih satu atau lebih jurusan"
              class="w-full multiselect-custom"
              :showSelectAll="false"
            />
            <Button
              v-if="selectedJurusan.length"
              label="Reset Pilihan"
              severity="danger"
              class="mt-4 w-full"
              @click="selectedJurusan = []"
            />
          </div>
        </div>

        <!-- ==================== TAB PRODI ==================== -->
        <div v-else-if="activeTab === 'Prodi'">
          <h2 class="text-2xl font-bold text-blue-800 mb-4">Pilih Prodi</h2>
          <div class="flex flex-col items-center gap-2 w-full max-w-xl mx-auto">
            <MultiSelect
              v-model="selectedProdi"
              :options="props.prodi"
              optionLabel="nama_prodi"
              optionValue="id"
              placeholder="Pilih satu atau lebih prodi"
              class="w-full multiselect-custom"
              :showSelectAll="false"
            />
            <Button
              v-if="selectedProdi.length"
              label="Reset Pilihan"
              severity="danger"
              class="mt-4 w-full"
              @click="selectedProdi = []"
            />
          </div>
        </div>

        <!-- ==================== TAB KELAS ==================== -->
        <div v-else-if="activeTab === 'Kelas'">
          <h2 class="text-2xl font-bold text-blue-800 mb-4">Pilih Kelas</h2>
          <div class="flex flex-col items-center gap-2 w-full max-w-xl mx-auto">
            <MultiSelect
              v-model="selectedKelas"
              :options="props.kelas"
              optionLabel="nama_kelas"
              optionValue="id"
              placeholder="Pilih satu atau lebih kelas"
              class="w-full multiselect-custom"
              :showSelectAll="false"
            />
            <Button
              v-if="selectedKelas.length"
              label="Reset Pilihan"
              severity="danger"
              class="mt-4 w-full"
              @click="selectedKelas = []"
            />
          </div>
        </div>

        <!-- Hasil Chart & Statistik -->
        <div v-if="props.stats && props.stats.chart.datasets.length > 0" class="mt-12 flex flex-col gap-8">
          
          <!-- ROW 1: Statistik Numerik -->
          <div>
              <h3 class="text-xl font-bold text-blue-800 mb-4 text-left">Statistik Total Skor</h3>
              <div v-if="props.stats.chart.datasets.length <= 2" class="space-y-4">
                  <div v-for="(dataset, index) in props.stats.chart.datasets" :key="index" class="p-5 border rounded-xl shadow-lg bg-gray-50 text-left">
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
              <div v-else :class="['grid gap-3', 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4']">
                  <div v-for="(dataset, index) in props.stats.chart.datasets" :key="index" class="p-3 border rounded-lg shadow-md bg-gray-50 text-left">
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

          <!-- ROW 2: Charts -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Bar Chart -->
              <div class="p-6 border rounded-xl shadow-lg bg-gray-50">
                <h3 class="text-xl font-bold text-blue-800 mb-5 text-left">Grafik Perbandingan Total Skor</h3>
                <Chart 
                  type="bar" 
                  :data="props.stats.chart" 
                  :options="barChartOptions"
                  style="height: 450px;"  
                />
              </div>

              <!-- Pie Chart -->
              <div v-if="props.stats.levelPieChart" class="p-6 border rounded-xl shadow-lg bg-gray-50">
                  <h3 class="text-xl font-bold text-blue-800 mb-5 text-left">Distribusi Level Mahasiswa</h3>
                  <Chart type="pie" :data="props.stats.levelPieChart" />
              </div>
          </div>
        </div>
        <div v-else-if="['Jurusan', 'Prodi', 'Kelas'].includes(activeTab) && (selectedJurusan.length > 0 || selectedProdi.length > 0 || selectedKelas.length > 0) && !props.stats?.chart.datasets.length" class="mt-10">
            <p class="text-gray-500">Tidak ada data statistik untuk ditampilkan pada pilihan saat ini.</p>
        </div>


        <!-- ==================== TAB INDIVIDU ==================== -->
        <div v-else-if="activeTab === 'Individu'">
          <h2 class="text-2xl font-bold text-blue-800 mb-6">Cek Hasil EPT Individu</h2>

          <div class="max-w-md mx-auto text-left bg-white border border-gray-300 rounded-xl shadow-lg p-6">
            <div class="mb-4">
              <label for="nim" class="block text-gray-700 font-semibold mb-2">NIM / NIK</label>
              <input
                id="nim"
                v-model="form.nim"
                type="text"
                placeholder="Masukkan NIM / NIK"
                class="w-full border border-gray-300 text-gray-800 placeholder-gray-400 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div class="mb-6">
              <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                placeholder="Masukkan Password"
                class="w-full border border-gray-300 text-gray-800 placeholder-gray-400 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            
            <p v-if="errors.individu" class="text-red-600 text-sm mb-4">{{ errors.individu }}</p>

            <Button
              label="Lihat Hasil"
              class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded-lg transition"
              @click="handleCheckResult"
              :disabled="form.processing"
            />
             <div v-if="form.processing" class="mt-4 text-center">
                <ProgressSpinner style="width:30px; height:30px" />
             </div>
          </div>

          <!-- Hasil EPT Individu -->
          <div v-if="props.mahasiswa" class="text-center mt-10">
            <h3 class="text-2xl font-bold text-gray-800">Peserta EPT: {{ props.mahasiswa.nama }}</h3>
          </div>

          <div v-if="props.eptResults" class="mt-10 flex flex-wrap justify-center gap-6">
            <div v-for="(r, index) in props.eptResults" :key="index"
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
              <!-- New Level Display -->
              <div v-if="r.level" class="text-center mt-4 pt-4 border-t-2 border-gray-200">
                <p class="text-black font-bold uppercase">Level</p>
                <p class="text-black font-bold text-xl">{{ r.level }}</p>
              </div>
            </div>
          </div>

        </div>
      </section>
    </Layout>
  </div>
</template>

<style scoped>
/* Custom styling for MultiSelect tokens to stack vertically */
.multiselect-custom .p-multiselect-label {
  display: flex;
  flex-wrap: wrap; /* Allow tokens to wrap to the next line */
  padding: 0.5rem 0.75rem; /* Adjust padding as needed */
}

.multiselect-custom .p-multiselect-token {
  display: block; /* Make each token take full width */
  margin-bottom: 4px; /* Add some space between tokens */
  width: 100%; /* Ensure it takes full width */
}

/* Adjust the container if needed */
.multiselect-custom .p-multiselect-label-container {
  display: block; /* Ensure the container allows vertical stacking */
}
</style>
