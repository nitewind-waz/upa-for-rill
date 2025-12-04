<script setup>
import { ref, computed } from 'vue' 
import { router, usePage } from '@inertiajs/vue3'
import Layout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    jadwal: Array,
    jadwalPublik: Array, 
    jurusan: Array,
    prodi: Array,
    kelas: Array,
    gedung: Array,
    ruang: Array,
    errors: Object 
})

const page = usePage()

const isEditing = ref(false);

const form = ref({
    id: null,
    jurusan_id: '',
    prodi_id: '',
    kelas_id: '',
    ruang_id: '', 
    gedung_id: '',
    tanggal: '',
    waktu_mulai: '',
    waktu_selesai: ''
})

const formPublik = ref({
    ruang_id: '',
    gedung_id: '',
    tanggal: '',
    jam_mulai: '',
    jam_selesai: '',
    nik: '',
    nama_lengkap: '',
})

// Memfilter daftar prodi berdasarkan jurusan yang dipilih
const filteredProdiList = computed(() => {
    if (!form.value.jurusan_id) return [];
    return props.prodi.filter(p => p.jurusan_id == form.value.jurusan_id);
});

// Memfilter daftar kelas berdasarkan prodi yang dipilih
const filteredKelasList = computed(() => {
    if (!form.value.prodi_id) return [];
    return props.kelas.filter(k => k.prodi_id == form.value.prodi_id);
});

const handleJurusanChange = () => {
    form.value.prodi_id = '';
    form.value.kelas_id = '';
};

const handleProdiChange = () => {
    form.value.kelas_id = '';
};


const resetForm = () => {
    form.value = {
        id: null,
        jurusan_id: '',
        prodi_id: '',
        kelas_id: '',
        ruang_id: '',
        gedung_id: '',
        tanggal: '',
        waktu_mulai: '',
        waktu_selesai: ''
    };
    isEditing.value = false;
};

const resetFormPublik = () => {
    formPublik.value = {
        ruang_id: '',
        gedung_id: '',
        tanggal: '',
        jam_mulai: '',
        jam_selesai: '',
        nik: '',
        nama_lengkap: '',
    }
}

//Helpers
const formatTanggalIndonesia = (dateString) => {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        // date.setDate(date.getDate() + 1); 

        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };

        return date.toLocaleDateString('id-ID', options);
    } catch (e) {
        console.error("Gagal memformat tanggal:", e);
        return dateString;
    }
};


const addSchedule = () => {
    router.post('/admin/jadwal', form.value, {
        preserveScroll: true,
        onSuccess: () => {
            resetForm();
        },
        onError: (errors) => {
            console.error('Validation Errors:', errors);
        }
    })
}

const addPublicSchedule = () => {
    router.post('/admin/jadwal-publik', formPublik.value, {
        preserveScroll: true,
        onSuccess: () => {
            resetFormPublik();
        },
        onError: (errors) => {
            console.error('Validation Errors:', errors)
        }
    })
}

const updateSchedule = () => {
    if (!form.value.id) return;
    
    router.put(`/admin/jadwal/${form.value.id}`, form.value, {
        preserveScroll: true,
        onSuccess: () => {
            resetForm();
        },
        onError: (errors) => {
            console.error('Validation Errors:', errors);
        }
    })
}

const saveSchedule = () => {
    if (isEditing.value) {
        updateSchedule();
    } else {
        addSchedule();
    }
}

const editSchedule = (schedule) => {
    isEditing.value = true;
    
    form.value = { 
        id: schedule.id,
        jurusan_id: schedule.jurusan_id,
        prodi_id: schedule.prodi_id,
        kelas_id: schedule.kelas_id,
        ruang_id: schedule.ruang_id,
        gedung_id: schedule.gedung_id,
        tanggal: schedule.tanggal ? new Date(schedule.tanggal).toISOString().split('T')[0] : '',
        waktu_mulai: schedule.waktu_mulai,
        waktu_selesai: schedule.waktu_selesai,
    };
};


const deleteSchedule = (id) => {
    if (confirm('Yakin ingin menghapus jadwal ini?')) {
        router.delete(`/admin/jadwal/${id}`, {
            preserveScroll: true,
        })
    }
}

const deletePublicSchedule = (id) => {
    if (confirm('Yakin ingin menghapus jadwal publik ini?')) {
        router.delete(`/admin/jadwal-publik/${id}`, {
            preserveScroll: true,
        })
    }
}
</script>

<template>
    <Layout>
        <Head :title="isEditing ? 'Edit Jadwal EPT' : 'Kelola Jadwal EPT'" />
        
        <div class="space-y-6">

            <h1 class="text-3xl font-bold text-gray-800">{{ isEditing ? 'Edit Jadwal EPT' : 'Kelola Jadwal EPT' }}</h1>

            <div v-if="page.props.success"
                class="p-4 rounded bg-green-100 text-green-700">
                {{ page.props.success }}
            </div>

            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</h2>

                <form @submit.prevent="saveSchedule" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div>
                        <label for="jurusan" class="text-gray-700 font-medium">Jurusan</label>
                        <select 
                            id="jurusan"
                            v-model="form.jurusan_id" 
                            @change="handleJurusanChange" 
                            class="mt-1 border p-2 w-full rounded"
                            :class="{'border-red-500': errors.jurusan_id}">
                            <option value="" disabled>Pilih Jurusan</option>
                            <option v-for="j in props.jurusan" :key="j.id" :value="j.id">
                                {{ j.nama_jurusan }}
                            </option>
                        </select>
                        <div v-if="errors.jurusan_id" class="text-red-500 text-sm">{{ errors.jurusan_id }}</div>
                    </div>

                    <div>
                        <label for="prodi" class="text-gray-700 font-medium">Prodi</label>
                        <select 
                            id="prodi"
                            v-model="form.prodi_id" 
                            @change="handleProdiChange" :disabled="!form.jurusan_id" 
                            class="mt-1 border p-2 w-full rounded"
                            :class="{'border-red-500': errors.prodi_id, 'bg-gray-100': !form.jurusan_id}">
                            <option value="" disabled>Pilih Prodi</option>
                            <option v-for="p in filteredProdiList" :key="p.id" :value="p.id">
                                {{ p.nama_prodi }}
                            </option>
                        </select>
                        <div v-if="errors.prodi_id" class="text-red-500 text-sm">{{ errors.prodi_id }}</div>
                    </div>

                    <div>
                        <label for="kelas" class="text-gray-700 font-medium">Kelas</label>
                        <select 
                            id="kelas"
                            v-model="form.kelas_id" 
                            :disabled="!form.prodi_id" class="mt-1 border p-2 w-full rounded"
                            :class="{'border-red-500': errors.kelas_id, 'bg-gray-100': !form.prodi_id}">
                            <option value="" disabled>Pilih Kelas</option>
                            <option v-for="k in filteredKelasList" :key="k.id" :value="k.id">
                                {{ k.nama_kelas }}
                            </option>
                        </select>
                        <div v-if="errors.kelas_id" class="text-red-500 text-sm">{{ errors.kelas_id }}</div>
                    </div>
                    
                    <div>
                        <label for="ruang" class="text-gray-700 font-medium">Tempat (Ruang)</label>
                        <select id="ruang" v-model="form.ruang_id" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.ruang_id}">
                            <option value="" disabled>Pilih Ruang</option>
                            <option v-for="r in props.ruang" :key="r.id" :value="r.id">{{ r.nama }}</option>
                        </select>
                        <div v-if="errors.ruang_id" class="text-red-500 text-sm">{{ errors.ruang_id }}</div>
                    </div>

                    <div>
                        <label for="gedung" class="text-gray-700 font-medium">Gedung</label>
                        <select id="gedung" v-model="form.gedung_id" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.gedung_id}">
                           <option value="" disabled>Pilih Gedung</option>
                           <option v-for="g in props.gedung" :key="g.id" :value="g.id">{{ g.nama }}</option>
                        </select>
                        <div v-if="errors.gedung_id" class="text-red-500 text-sm">{{ errors.gedung_id }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Tanggal</label>
                        <input type="date" v-model="form.tanggal" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.tanggal}">
                        <div v-if="errors.tanggal" class="text-red-500 text-sm">{{ errors.tanggal }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Waktu Mulai (00:00)</label>
                        <input type="time" v-model="form.waktu_mulai" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.waktu_mulai}" step="60"> 
                        <div v-if="errors.waktu_mulai" class="text-red-500 text-sm">{{ errors.waktu_mulai }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Waktu Selesai (00:00)</label>
                        <input type="time" v-model="form.waktu_selesai" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.waktu_selesai}" step="60">
                        <div v-if="errors.waktu_selesai" class="text-red-500 text-sm">{{ errors.waktu_selesai }}</div>
                    </div>
                    
                    <div class="md:col-span-3 mt-4 flex space-x-3">
                        <button type="submit"
                            class="px-5 py-2 rounded shadow-md transition duration-150"
                            :class="isEditing ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-blue-600 text-white hover:bg-blue-700'">
                            {{ isEditing ? 'Simpan Perubahan' : 'Tambah Jadwal' }}
                        </button>

                        <button v-if="isEditing" 
                            @click.prevent="resetForm" 
                            type="button"
                            class="bg-gray-500 text-white px-5 py-2 rounded shadow-md hover:bg-gray-600 transition duration-150">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-4">Tambah Jadwal Publik</h2>
                <form @submit.prevent="addPublicSchedule" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="ruang" class="text-gray-700 font-medium">Tempat (Ruang)</label>
                        <select id="ruang" v-model="formPublik.ruang_id" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.ruang_id}">
                            <option value="" disabled>Pilih Ruang</option>
                            <option v-for="r in props.ruang" :key="r.id" :value="r.id">{{ r.nama }}</option>
                        </select>
                        <div v-if="errors.ruang_id" class="text-red-500 text-sm">{{ errors.ruang_id }}</div>
                    </div>
                     <div>
                        <label for="gedung" class="text-gray-700 font-medium">Gedung</label>
                        <select id="gedung" v-model="formPublik.gedung_id" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.gedung_id}">
                           <option value="" disabled>Pilih Gedung</option>
                           <option v-for="g in props.gedung" :key="g.id" :value="g.id">{{ g.nama }}</option>
                        </select>
                        <div v-if="errors.gedung_id" class="text-red-500 text-sm">{{ errors.gedung_id }}</div>
                    </div>
                     <div>
                        <label class="text-gray-700 font-medium">Tanggal</label>
                        <input type="date" v-model="formPublik.tanggal" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.tanggal}">
                        <div v-if="errors.tanggal" class="text-red-500 text-sm">{{ errors.tanggal }}</div>
                    </div>
                     <div>
                        <label class="text-gray-700 font-medium">Waktu Mulai (00:00)</label>
                        <input type="time" v-model="formPublik.jam_mulai" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.jam_mulai}" step="60"> 
                        <div v-if="errors.jam_mulai" class="text-red-500 text-sm">{{ errors.jam_mulai }}</div>
                    </div>
                     <div>
                        <label class="text-gray-700 font-medium">Waktu Selesai (00:00)</label>
                        <input type="time" v-model="formPublik.jam_selesai" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.jam_selesai}" step="60">
                        <div v-if="errors.jam_selesai" class="text-red-500 text-sm">{{ errors.jam_selesai }}</div>
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium">NIK</label>
                        <input type="text" v-model="formPublik.nik" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.nik}">
                        <div v-if="errors.nik" class="text-red-500 text-sm">{{ errors.nik }}</div>
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium">Nama Lengkap</label>
                        <input type="text" v-model="formPublik.nama_lengkap" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': errors.nama_lengkap}">
                        <div v-if="errors.nama_lengkap" class="text-red-500 text-sm">{{ errors.nama_lengkap }}</div>
                    </div>
                    <div class="md:col-span-3 mt-4 flex space-x-3">
                        <button type="submit"
                            class="bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 rounded shadow-md transition duration-150">
                            Tambah Jadwal Publik
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-4">Daftar Jadwal</h2>

                <table class="w-full border rounded overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Jurusan</th>
                            <th class="p-3 text-left">Prodi</th>
                            <th class="p-3 text-left">Kelas</th>
                            <th class="p-3 text-left">Ruang</th>
                            <th class="p-3 text-left">Gedung</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Waktu</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="schedule in props.jadwal" 
                            :key="schedule.id" 
                            class="border-t hover:bg-gray-50">
                            
                            <td class="p-3">{{ schedule.jurusan?.nama_jurusan || '-' }}</td>
                            <td class="p-3">{{ schedule.prodi?.nama_prodi || '-' }}</td>
                            <td class="p-3">{{ schedule.kelas?.nama_kelas || '-' }}</td>
                            <td class="p-3">{{ schedule.ruang?.nama || '-' }}</td>
                            <td class="p-3">{{ schedule.gedung?.nama || '-' }}</td>
                            
                            <td class="p-3">
                                {{ formatTanggalIndonesia(schedule.tanggal) }}
                            </td>
                            
                            <td class="p-3 whitespace-nowrap">
                                {{ schedule.waktu_mulai?.substring(0, 5) || '00:00' }} - 
                                {{ schedule.waktu_selesai?.substring(0, 5) || '00:00' }}
                            </td>
                            
                            <td class="p-3 flex justify-center space-x-2">
                                <button @click="editSchedule(schedule)"
                                        class="bg-orange-500 text-white text-sm px-3 py-1 rounded shadow-md hover:bg-orange-600 transition duration-150">
                                    Edit
                                </button>
                                <button @click="deleteSchedule(schedule.id)"
                                        class="bg-red-600 text-white text-sm px-3 py-1 rounded shadow-md hover:bg-red-700 transition duration-150">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        <tr v-if="props.jadwal.length === 0">
                            <td colspan="8" class="p-4 text-center text-gray-500">
                                Belum ada jadwal.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-4">Daftar Jadwal Publik</h2>
                <table class="w-full border rounded overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">NIK</th>
                            <th class="p-3 text-left">Nama Lengkap</th>
                            <th class="p-3 text-left">Ruang</th>
                            <th class="p-3 text-left">Gedung</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Waktu</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="jp in props.jadwalPublik" :key="jp.id" class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ jp.publik?.nik || '-' }}</td>
                            <td class="p-3">{{ jp.publik?.nama_lengkap || '-' }}</td>
                            <td class="p-3">{{ jp.ruang?.nama || '-' }}</td>
                            <td class="p-3">{{ jp.gedung?.nama || '-' }}</td>
                            <td class="p-3">{{ formatTanggalIndonesia(jp.tanggal) }}</td>
                            <td class="p-3 whitespace-nowrap">
                                {{ jp.jam_mulai?.substring(0, 5) || '00:00' }} - 
                                {{ jp.jam_selesai?.substring(0, 5) || '00:00' }}
                            </td>
                            <td class="p-3 flex justify-center space-x-2">
                                <button @click="deletePublicSchedule(jp.id)"
                                        class="bg-red-600 text-white text-sm px-3 py-1 rounded shadow-md hover:bg-red-700 transition duration-150">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="props.jadwalPublik.length === 0">
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                Belum ada jadwal publik.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Layout> 
</template>
