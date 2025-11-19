<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';

// 1. Menerima props untuk data kursus
interface Course {
    id: number;
    judul: string;
    deskripsi: string;
    ketentuan: string;
    jadwal: string;
    jenis: string;
    sistem_pembelajaran: string;
    // ... properti lain
}

const props = defineProps<{
    course: Course;
}>();

// Form Inertia
// 2. Mengisi formulir dengan data dari props
const form = useForm({
    judul: props.course.judul,
    deskripsi: props.course.deskripsi,
    ketentuan: props.course.ketentuan,
    jadwal: props.course.jadwal,
    jenis: props.course.jenis,
    sistem_pembelajaran: props.course.sistem_pembelajaran,
});

// submit
const submit = () => {
    // 3. Mengubah ke form.put dengan endpoint yang sesuai
    form.put(`/admin/course/${props.course.id}`, {
        onSuccess: () => {
            // sukses → redirect ke index → modal sukses ditampilkan lewat flash session
        }
    });
};

// dropdown options (tetap sama)
const jenisOptions = [
    { label: "Gratis", value: "Gratis" },
    { label: "Berbayar", value: "Berbayar" },
];

const sistemOptions = [
    { label: "Daring", value: "Daring" },
    { label: "Luring", value: "Luring" },
    { label: "Hybrid", value: "Hybrid" },
];
</script>

<template>
    <AdminLayout title="Edit Kursus">
        <Head :title="`Edit Kursus: ${props.course.judul}`" />

        <div class="p-6 space-y-6 max-w-3xl mx-auto">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 
                            flex items-center justify-center shadow-lg">
                    <i class="pi pi-pencil text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Kursus: {{ props.course.judul }}</h1>
                    <p class="text-gray-500 text-sm">Ubah informasi kursus di bawah ini.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kursus</label>
                    <InputText 
                        v-model="form.judul" 
                        class="w-full p-inputtext-sm"
                        :class="{'p-invalid': form.errors.judul}"
                    />
                    <small v-if="form.errors.judul" class="text-red-600 text-sm">
                        {{ form.errors.judul }}
                    </small>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <Textarea 
                        v-model="form.deskripsi" 
                        rows="4" 
                        autoResize
                        class="w-full"
                        :class="{'p-invalid': form.errors.deskripsi}"
                    />
                    <small v-if="form.errors.deskripsi" class="text-red-600 text-sm">
                        {{ form.errors.deskripsi }}
                    </small>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ketentuan</label>
                    <Textarea 
                        v-model="form.ketentuan"
                        rows="3"
                        autoResize
                        class="w-full"
                        :class="{'p-invalid': form.errors.ketentuan}"
                    />
                    <small v-if="form.errors.ketentuan" class="text-red-600 text-sm">
                        {{ form.errors.ketentuan }}
                    </small>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal</label>
                    <InputText 
                        v-model="form.jadwal" 
                        class="w-full p-inputtext-sm"
                        :class="{'p-invalid': form.errors.jadwal}"
                    />
                    <small v-if="form.errors.jadwal" class="text-red-600 text-sm">
                        {{ form.errors.jadwal }}
                    </small>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kursus</label>
                        <Dropdown 
                            v-model="form.jenis"
                            :options="jenisOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Pilih jenis"
                            class="w-full"
                            :class="{'p-invalid': form.errors.jenis}"
                        />
                        <small v-if="form.errors.jenis" class="text-red-600 text-sm">
                            {{ form.errors.jenis }}
                        </small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sistem Pembelajaran</label>
                        <Dropdown 
                            v-model="form.sistem_pembelajaran"
                            :options="sistemOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Pilih sistem"
                            class="w-full"
                            :class="{'p-invalid': form.errors.sistem_pembelajaran}"
                        />
                        <small v-if="form.errors.sistem_pembelajaran" class="text-red-600 text-sm">
                            {{ form.errors.sistem_pembelajaran }}
                        </small>
                    </div>
                </div>

            </div>

            <div class="flex justify-between">

                <button
                    @click="$inertia.visit('/admin/course')"
                    class="px-5 py-2.5 rounded-lg bg-gray-200 hover:bg-gray-300 
                            text-gray-700 font-medium transition-all"
                >
                    Batal
                </button>

                <Button
                    label="Perbarui"
                    icon="pi pi-save"
                    class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 border-0 
                            rounded-xl shadow-md text-white font-semibold hover:scale-105 
                            transition-all"
                    @click="submit"
                    :loading="form.processing"
                />
            </div>

        </div>

    </AdminLayout>
</template>