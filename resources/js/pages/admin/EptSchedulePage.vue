<script setup>
import { ref, computed } from 'vue' 
import { router, usePage } from '@inertiajs/vue3'
import Layout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import Chip from 'primevue/chip';

const props = defineProps({
    jadwal: Array, 
    errors: Object 
})

const page = usePage()

const isEditing = ref(false);
const editingSchedule = ref(null);

const jurusanList = ref([
    {
        nama: 'Teknik Informatika dan Komputer',
        prodi: [
            { nama: 'D4 Teknik Informatika' },
            { nama: 'D3 Teknik Informatika' },
            { nama: 'D4 Teknik Komputer' },
        ],
    },
    {
        nama: 'Administrasi Niaga',
        prodi: [{ nama: 'D4 Administrasi Bisnis' }],
    },
]);

const rawKelasList = [
    
    { prodi: 'D4 Teknik Informatika', nama: '1A-D4TI' },
    { prodi: 'D4 Teknik Informatika', nama: '1B-D4TI' },
    { prodi: 'D4 Teknik Informatika', nama: '2A-D4TI' },
    { prodi: 'D4 Teknik Informatika', nama: '2B-D4TI' },
   
    { prodi: 'D3 Teknik Informatika', nama: '1A-D3TI' },
    { prodi: 'D3 Teknik Informatika', nama: '2A-D3TI' },
   
    { prodi: 'D4 Teknik Komputer', nama: '1A-D4TK' },
    { prodi: 'D4 Teknik Komputer', nama: '2A-D4TK' },
   
    { prodi: 'D4 Administrasi Bisnis', nama: '1A-D4AB' },
    { prodi: 'D4 Administrasi Bisnis', nama: '2A-D4AB' },
];

const form = ref({
    id: null,
    jurusan: '',
    prodi: '',
    kelas: '',
    tempat: '', 
    gedung: '',
    tanggal: '',
    waktu_mulai: '',
    waktu_selesai: ''
})

//memfilter daftar prodi berdasarkan jurusan yang dipilih
const filteredProdiList = computed(() => {
    const selectedJurusan = jurusanList.value.find(j => j.nama === form.value.jurusan);
    return selectedJurusan ? selectedJurusan.prodi.map(p => p.nama) : [];
});

//memfilter daftar kelas berdasarkan prodi yang dipilih
const filteredKelasList = computed(() => {
    if (!form.value.prodi) return [];
    return rawKelasList
        .filter(kelas => kelas.prodi === form.value.prodi)
        .map(kelas => kelas.nama);
});

const handleJurusanChange = () => {
    form.value.prodi = '';
    form.value.kelas = '';
};

const handleProdiChange = () => {
    form.value.kelas = '';
};


const resetForm = () => {
    form.value = {
        id: null,
        jurusan: '',
        prodi: '',
        kelas: '',
        tempat: '',
        gedung: '',
        tanggal: '',
        waktu_mulai: '',
        waktu_selesai: ''
    };
    isEditing.value = false;
    editingSchedule.value = null;
};

//Helpers
const formatTanggalIndonesia = (dateString) => {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        date.setDate(date.getDate() + 1); 

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
    editingSchedule.value = schedule;
    
    form.value = { 
        ...schedule,
        tanggal: schedule.tanggal ? new Date(schedule.tanggal).toISOString().split('T')[0] : ''
    };
};


const deleteSchedule = (id) => {
    if (confirm('Yakin ingin menghapus jadwal ini?')) {
        router.delete(`/admin/jadwal/${id}`, {
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

            <div v-if="page.props.flash?.success"
                class="p-4 rounded bg-green-100 text-green-700">
                {{ page.props.flash.success }}
            </div>

            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold mb-4">{{ isEditing ? 'Edit Jadwal' : 'Tambah Jadwal Baru' }}</h2>

                <form @submit.prevent="saveSchedule" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <div>
                        <label for="jurusan" class="text-gray-700 font-medium">Jurusan</label>
                        <select 
                            id="jurusan"
                            v-model="form.jurusan" 
                            @change="handleJurusanChange" 
                            class="mt-1 border p-2 w-full rounded"
                            :class="{'border-red-500': page.props.errors.jurusan}">
                            <option value="" disabled>Pilih Jurusan</option>
                            <option v-for="jurusan in jurusanList" :key="jurusan.nama" :value="jurusan.nama">
                                {{ jurusan.nama }}
                            </option>
                        </select>
                        <div v-if="page.props.errors.jurusan" class="text-red-500 text-sm">{{ page.props.errors.jurusan }}</div>
                    </div>

                    <div>
                        <label for="prodi" class="text-gray-700 font-medium">Prodi</label>
                        <select 
                            id="prodi"
                            v-model="form.prodi" 
                            @change="handleProdiChange" :disabled="!form.jurusan" 
                            class="mt-1 border p-2 w-full rounded"
                            :class="{'border-red-500': page.props.errors.prodi, 'bg-gray-100': !form.jurusan}">
                            <option value="" disabled>Pilih Prodi</option>
                            <option v-for="prodi in filteredProdiList" :key="prodi" :value="prodi">
                                {{ prodi }}
                            </option>
                            <option v-if="isEditing && form.prodi && !filteredProdiList.includes(form.prodi)" :value="form.prodi">
                                {{ form.prodi }} (Saat ini)
                            </option>
                        </select>
                        <div v-if="page.props.errors.prodi" class="text-red-500 text-sm">{{ page.props.errors.prodi }}</div>
                    </div>

                    <div>
                        <label for="kelas" class="text-gray-700 font-medium">Kelas</label>
                        <select 
                            id="kelas"
                            v-model="form.kelas" 
                            :disabled="!form.prodi" class="mt-1 border p-2 w-full rounded"
                            :class="{'border-red-500': page.props.errors.kelas, 'bg-gray-100': !form.prodi}">
                            <option value="" disabled>Pilih Kelas</option>
                            <option v-for="kelas in filteredKelasList" :key="kelas" :value="kelas">
                                {{ kelas }}
                            </option>
                            <option v-if="isEditing && form.kelas && !filteredKelasList.includes(form.kelas)" :value="form.kelas">
                                {{ form.kelas }} (Saat ini)
                            </option>
                        </select>
                        <div v-if="page.props.errors.kelas" class="text-red-500 text-sm">{{ page.props.errors.kelas }}</div>
                    </div>
                    
                    <div>
                        <label class="text-gray-700 font-medium">Tempat (Ruang)</label>
                        <input v-model="form.tempat" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': page.props.errors.tempat}">
                        <div v-if="page.props.errors.tempat" class="text-red-500 text-sm">{{ page.props.errors.tempat }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Gedung</label>
                        <input v-model="form.gedung" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': page.props.errors.gedung}">
                        <div v-if="page.props.errors.gedung" class="text-red-500 text-sm">{{ page.props.errors.gedung }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Tanggal</label>
                        <input type="date" v-model="form.tanggal" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': page.props.errors.tanggal}">
                        <div v-if="page.props.errors.tanggal" class="text-red-500 text-sm">{{ page.props.errors.tanggal }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Waktu Mulai (00:00)</label>
                        <input type="time" v-model="form.waktu_mulai" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': page.props.errors.waktu_mulai}" step="60"> 
                        <div v-if="page.props.errors.waktu_mulai" class="text-red-500 text-sm">{{ page.props.errors.waktu_mulai }}</div>
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium">Waktu Selesai (00:00)</label>
                        <input type="time" v-model="form.waktu_selesai" class="mt-1 border p-2 w-full rounded" :class="{'border-red-500': page.props.errors.waktu_selesai}" step="60">
                        <div v-if="page.props.errors.waktu_selesai" class="text-red-500 text-sm">{{ page.props.errors.waktu_selesai }}</div>
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
                            
                            <td class="p-3">{{ schedule.jurusan || '-' }}</td>
                            <td class="p-3">{{ schedule.prodi || '-' }}</td>
                            <td class="p-3">{{ schedule.kelas || '-' }}</td>
                            
                            <td class="p-3">{{ schedule.tempat || '-' }}</td>
                            <td class="p-3">{{ schedule.gedung || '-' }}</td>
                            
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
        </div>
    </Layout> 
</template>
