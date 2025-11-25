<script setup>
import { ref, watch } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import Layout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
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

// === Chart Options ===
const barChartOptions = {
  maintainAspectRatio: false,
  responsive: true,
  plugins: {
    legend: {
      position: 'bottom',
      labels: { color: '#64748b', padding: 20, font: { family: 'ui-sans-serif' } }
    }
  },
  scales: {
    x: {
      ticks: { color: '#64748b' },
      grid: { display: false }
    },
    y: {
      ticks: { color: '#64748b' },
      grid: { color: '#e2e8f0', borderDash: [5, 5] },
      beginAtZero: true
    }
  }
}
</script>

<template>
  <Head title="Hasil EPT" />

  <Layout>
    <div class="min-h-screen flex flex-col bg-slate-50">

        <section class="relative w-full h-[350px] flex items-center justify-center overflow-hidden shadow-md">
            <div 
                class="absolute inset-0 w-full h-full bg-cover bg-center z-0 animate-[pulse_15s_ease-in-out_infinite]"
                style="background-image: url('/banner-upa.jpeg');" 
            ></div>
            <div class="absolute inset-0 bg-slate-900/50 z-10"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-50 z-20"></div>

            <div class="relative z-30 container mx-auto px-6 text-center pt-8">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-600/30 border border-blue-400/50 text-blue-50 text-xs font-bold mb-4 backdrop-blur-md uppercase tracking-widest">
                    Laporan Akademik
                </span>
                <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight drop-shadow-lg mb-3">
                    Hasil Tes EPT
                </h1>
                <p class="text-white/90 text-lg max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
                    Pantau perkembangan skor EPT Anda atau lihat statistik pencapaian berdasarkan jurusan dan prodi.
                </p>
            </div>
        </section>

        <section class="relative pb-20 -mt-10 z-30">
            <div class="container mx-auto px-6">
                
                <div class="flex justify-center mb-12">
                    <div class="bg-blue-50 p-1.5 rounded-xl shadow-lg border border-blue-100 inline-flex flex-wrap justify-center gap-1">
                        <button
                            v-for="tab in ['Jurusan','Prodi','Kelas','Individu']"
                            :key="tab"
                            @click="loadTabData(tab)"
                            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-300"
                            :class="activeTab === tab 
                                ? 'bg-blue-600 text-white shadow-md transform scale-105' 
                                : 'text-slate-500 hover:text-blue-600 hover:bg-blue-100'"
                        >
                            {{ tab }}
                        </button>
                    </div>
                </div>

                <transition name="fade" mode="out-in">
                    <div v-if="['Jurusan', 'Prodi', 'Kelas'].includes(activeTab)" class="space-y-10">
                        
                        <div class="max-w-3xl mx-auto bg-blue-50 rounded-2xl shadow-lg border border-blue-100 p-8 text-center">
                            <h2 class="text-xl font-bold text-slate-800 mb-2">Filter Data {{ activeTab }}</h2>
                            <p class="text-slate-500 text-sm mb-6">Pilih satu atau lebih {{ activeTab.toLowerCase() }} untuk melihat statistik.</p>
                            
                            <div class="max-w-xl mx-auto">
                                <div v-if="activeTab === 'Jurusan'">
                                    <MultiSelect v-model="selectedJurusan" :options="props.jurusan" optionLabel="nama_jurusan" optionValue="id" placeholder="Pilih Jurusan..." class="w-full multiselect-custom" :showSelectAll="false" display="chip" />
                                    <Button v-if="selectedJurusan.length" label="Reset Filter" icon="pi pi-times" severity="secondary" text class="mt-2 !text-red-500" @click="selectedJurusan = []" />
                                </div>

                                <div v-else-if="activeTab === 'Prodi'">
                                    <MultiSelect v-model="selectedProdi" :options="props.prodi" optionLabel="nama_prodi" optionValue="id" placeholder="Pilih Prodi..." class="w-full multiselect-custom" :showSelectAll="false" display="chip" />
                                    <Button v-if="selectedProdi.length" label="Reset Filter" icon="pi pi-times" severity="secondary" text class="mt-2 !text-red-500" @click="selectedProdi = []" />
                                </div>

                                <div v-else-if="activeTab === 'Kelas'">
                                    <MultiSelect v-model="selectedKelas" :options="props.kelas" optionLabel="nama_kelas" optionValue="id" placeholder="Pilih Kelas..." class="w-full multiselect-custom" :showSelectAll="false" display="chip" />
                                    <Button v-if="selectedKelas.length" label="Reset Filter" icon="pi pi-times" severity="secondary" text class="mt-2 !text-red-500" @click="selectedKelas = []" />
                                </div>
                            </div>
                        </div>

                        <div v-if="props.stats && props.stats.chart.datasets.length > 0" class="space-y-8">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div v-for="(dataset, index) in props.stats.chart.datasets" :key="index" 
                                    class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition-shadow hover:border-blue-300 group">
                                    <h4 class="font-bold text-slate-800 mb-3 border-b border-slate-100 pb-2 truncate" :title="dataset.label">
                                        {{ dataset.label }}
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500">Tertinggi</span>
                                            <span class="font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ dataset.data[0] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500">Terendah</span>
                                            <span class="font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded">{{ dataset.data[1] }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500">Rata-rata</span>
                                            <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">{{ dataset.data[2] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="font-bold text-slate-800 text-lg">Perbandingan Skor</h3>
                                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-bold">Grafik Batang</span>
                                    </div>
                                    <div class="h-[400px]">
                                        <Chart type="bar" :data="props.stats.chart" :options="barChartOptions" class="h-full" />
                                    </div>
                                </div>

                                <div v-if="props.stats.levelPieChart" class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="font-bold text-slate-800 text-lg">Distribusi Level</h3>
                                        <span class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full font-bold">Diagram Lingkaran</span>
                                    </div>
                                    <div class="h-[400px] flex justify-center">
                                        <Chart type="pie" :data="props.stats.levelPieChart" class="h-full" />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div v-else-if="(selectedJurusan.length > 0 || selectedProdi.length > 0 || selectedKelas.length > 0) && !props.stats?.chart.datasets.length" 
                             class="text-center py-12 bg-white rounded-xl border border-dashed border-slate-300">
                            <i class="pi pi-chart-bar text-slate-300 text-4xl mb-3"></i>
                            <p class="text-slate-500">Data statistik belum tersedia untuk pilihan ini.</p>
                        </div>

                    </div>

                    <div v-else-if="activeTab === 'Individu'" class="space-y-10">
                        
                        <div class="max-w-md mx-auto bg-blue-50 rounded-2xl shadow-xl border border-blue-100 p-8">
                            <div class="text-center mb-6">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-3">
                                    <i class="pi pi-user text-xl"></i>
                                </div>
                                <h2 class="text-xl font-bold text-slate-800">Cek Hasil Pribadi</h2>
                                <p class="text-slate-500 text-xs">Masukkan kredensial untuk melihat riwayat tes.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">NIM / NIK</label>
                                    <input v-model="form.nim" type="text" placeholder="Nomor Induk Mahasiswa" 
                                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Password</label>
                                    <input v-model="form.password" type="password" placeholder="Kata sandi akun" 
                                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                </div>
                                
                                <div v-if="errors.individu" class="bg-red-50 text-red-600 text-sm p-3 rounded-lg flex items-center gap-2">
                                    <i class="pi pi-exclamation-circle"></i> {{ errors.individu }}
                                </div>

                                <Button label="Lihat Hasil Saya" class="w-full !bg-blue-600 !border-blue-600 hover:!bg-blue-700 mt-2" 
                                    @click="handleCheckResult" :loading="form.processing" />
                            </div>
                        </div>

                        <div v-if="props.mahasiswa" class="animate-fade-in-up">
                            <div class="flex items-center justify-center gap-3 mb-8">
                                <div class="h-px w-12 bg-slate-300"></div>
                                <h3 class="text-xl font-bold text-slate-700">
                                    Riwayat Tes: <span class="text-blue-600">{{ props.mahasiswa.nama }}</span>
                                </h3>
                                <div class="h-px w-12 bg-slate-300"></div>
                            </div>

                            <div v-if="props.eptResults" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">
                                <div v-for="(r, index) in props.eptResults" :key="index"
                                    class="w-full max-w-sm bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden hover:shadow-xl transition-all duration-300 group hover:-translate-y-1">
                                    
                                    <div class="bg-blue-600 p-4 text-white text-center relative overflow-hidden">
                                        <div class="absolute top-0 left-0 w-full h-full bg-white opacity-10 transform -skew-x-12"></div>
                                        <p class="text-xs uppercase tracking-widest opacity-80 mb-1">Tahun Tes</p>
                                        <p class="text-2xl font-bold">{{ r.tahun }}</p>
                                    </div>

                                    <div class="p-6 space-y-4">
                                        <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                                            <div class="flex items-center gap-2 text-slate-600">
                                                <i class="pi pi-headphones text-blue-400"></i> Listening
                                            </div>
                                            <span class="font-bold text-slate-800">{{ r.listening }}</span>
                                        </div>
                                        <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                                            <div class="flex items-center gap-2 text-slate-600">
                                                <i class="pi pi-book text-green-400"></i> Structure
                                            </div>
                                            <span class="font-bold text-slate-800">{{ r.structure }}</span>
                                        </div>
                                        <div class="flex justify-between items-center border-b border-slate-50 pb-2">
                                            <div class="flex items-center gap-2 text-slate-600">
                                                <i class="pi pi-eye text-orange-400"></i> Reading
                                            </div>
                                            <span class="font-bold text-slate-800">{{ r.reading }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 p-4 flex justify-between items-center border-t border-slate-100">
                                        <div>
                                            <p class="text-xs text-slate-500 uppercase font-bold">Total Score</p>
                                            <p class="text-xl font-black text-blue-700">{{ r.total_score }}</p>
                                        </div>
                                        <div class="text-right" v-if="r.level">
                                            <p class="text-xs text-slate-500 uppercase font-bold">Level</p>
                                            <Tag :value="r.level" severity="info" class="font-bold" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </transition>

            </div>
        </section>

    </div>
  </Layout>
</template>

<style scoped>
/* PrimeVue Override untuk MultiSelect agar lebih clean */
:deep(.multiselect-custom) {
    border-radius: 0.5rem;
    border-color: #e2e8f0;
    background-color: white;
}
:deep(.multiselect-custom:hover) {
    border-color: #3b82f6;
}
:deep(.multiselect-custom.p-focus) {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    border-color: #3b82f6;
}

/* Transition Fade */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Animation Utility */
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>