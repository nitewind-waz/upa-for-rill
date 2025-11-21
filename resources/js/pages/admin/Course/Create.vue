<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';

// Form Inertia
const form = useForm({
  judul: '',
  deskripsi: '',
  ketentuan: '',
  jadwal: '',
  jenis: '',
  sistem_pembelajaran: '',
});

// submit
const submit = () => {
  form.post('/admin/course/create', {
    onSuccess: () => {
      // sukses → redirect ke index → modal sukses ditampilkan lewat flash session
    }
  });
};

// dropdown options
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
  <AdminLayout title="Tambah Kursus">
    <Head title="Tambah Kursus" />

    <div class="p-6 space-y-6 max-w-3xl mx-auto">

      <!-- PAGE HEADER -->
      <div class="flex items-center gap-3 mb-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 
                    flex items-center justify-center shadow-lg">
          <i class="pi pi-plus text-white text-xl"></i>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Tambah Kursus Baru</h1>
          <p class="text-gray-500 text-sm">Lengkapi informasi berikut untuk menambah kursus.</p>
        </div>
      </div>

      <!-- FORM CARD -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 space-y-6">

        <!-- Judul -->
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

        <!-- Deskripsi -->
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

        <!-- Ketentuan -->
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

        <!-- Jadwal -->
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

        <!-- Row: Jenis + Sistem -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <!-- Jenis -->
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

          <!-- Sistem Pembelajaran -->
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

      <!-- BUTTONS -->
      <div class="flex justify-between">

        <button
          @click="$inertia.visit('/admin/course')"
          class="px-5 py-2.5 rounded-lg bg-gray-200 hover:bg-gray-300 
                 text-gray-700 font-medium transition-all"
        >
          Batal
        </button>

        <Button
          label="Simpan"
          icon="pi pi-check"
          class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 border-0 
                 rounded-xl shadow-md text-white font-semibold hover:scale-105 
                 transition-all"
          @click="submit"
          :loading="form.processing"
        />
      </div>

    </div>

  </AdminLayout>
</template>
