<template>
    <AdminLayout title="Print Certificate">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 p-6">
            <div class="max-w-3xl mx-auto">
                
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-700 rounded-3xl shadow-xl mb-4">
                        <i class="pi pi-print text-white text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-extrabold text-slate-800 mb-2">
                        Print EPT Certificate
                    </h1>
                    <p class="text-slate-500">Select a student to generate their English Proficiency Test certificate</p>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
                    
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center gap-3">
                            <i class="pi pi-users"></i>
                            Student Selection
                        </h2>
                    </div>

                    <!-- Card Body -->
                    <div class="p-8">
                        <div class="space-y-6">
                            
                            <!-- Dropdown Selection -->
                            <div>
                                <label for="mahasiswa" class="block text-sm font-bold text-slate-700 mb-3">
                                    <i class="pi pi-user text-blue-600 mr-2"></i>
                                    Select Student
                                </label>
                                <Dropdown 
                                    v-model="selectedMahasiswa" 
                                    :options="props.mahasiswas" 
                                    optionLabel="nama" 
                                    optionValue="id"
                                    placeholder="Choose a student..."
                                    class="w-full"
                                    :class="{ 'p-invalid': !selectedMahasiswa && showError }"
                                >
                                    <template #value="slotProps">
                                        <div v-if="slotProps.value" class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ getInitials(getMahasiswaName(slotProps.value)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800">{{ getMahasiswaName(slotProps.value) }}</div>
                                                <div class="text-sm text-slate-500">{{ getMahasiswaNim(slotProps.value) }}</div>
                                            </div>
                                        </div>
                                        <span v-else class="text-slate-400">{{ slotProps.placeholder }}</span>
                                    </template>
                                    <template #option="slotProps">
                                        <div class="flex items-center gap-3 py-2">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ getInitials(slotProps.option.nama) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800">{{ slotProps.option.nama }}</div>
                                                <div class="text-sm text-slate-500">NIM: {{ slotProps.option.nim }}</div>
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
                                <small v-if="!selectedMahasiswa && showError" class="text-red-500 mt-1 block">
                                    Please select a student first
                                </small>
                            </div>

                            <!-- Info Box -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                                <i class="pi pi-info-circle text-blue-600 text-xl mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-900 font-semibold mb-1">Information</p>
                                    <p class="text-sm text-blue-700">
                                        The certificate will be generated in a printable format. Make sure your printer is ready before proceeding.
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                <Button 
                                    label="Cancel" 
                                    icon="pi pi-times" 
                                    severity="secondary"
                                    text
                                    class="!text-slate-600 hover:!bg-slate-100"
                                    @click="router.visit('/admin/dashboard')"
                                />
                                <Button 
                                    label="Generate Certificate" 
                                    icon="pi pi-print" 
                                    :disabled="!selectedMahasiswa"
                                    class="!bg-gradient-to-r !from-blue-600 !to-indigo-600 !border-none hover:!from-blue-700 hover:!to-indigo-700 !shadow-lg hover:!shadow-xl transition-all !px-6"
                                    @click="printCertificate"
                                />
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Footer Note -->
                <div class="text-center mt-6">
                    <p class="text-sm text-slate-400">
                        <i class="pi pi-shield-check mr-1"></i>
                        Secure certificate generation powered by UPA Bahasa POLBAN
                    </p>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';

const props = defineProps<{
    mahasiswas: any[];
}>();

const selectedMahasiswa = ref<number | null>(null);
const showError = ref(false);

const getInitials = (name: string) => {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const getMahasiswaName = (id: number) => {
    const mahasiswa = props.mahasiswas.find(m => m.id === id);
    return mahasiswa ? mahasiswa.nama : '';
};

const getMahasiswaNim = (id: number) => {
    const mahasiswa = props.mahasiswas.find(m => m.id === id);
    return mahasiswa ? mahasiswa.nim : '';
};

const printCertificate = () => {
    if (selectedMahasiswa.value) {
        router.visit(`/admin/certificate/${selectedMahasiswa.value}`);
    } else {
        showError.value = true;
    }
};
</script>

<style scoped>
:deep(.p-dropdown) {
    border-radius: 0.75rem;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

:deep(.p-dropdown:hover) {
    border-color: #3b82f6;
}

:deep(.p-dropdown.p-focus) {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

:deep(.p-dropdown-panel) {
    border-radius: 0.75rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    margin-top: 0.5rem;
}

:deep(.p-dropdown-item) {
    border-radius: 0.5rem;
    margin: 0.25rem 0.5rem;
}

:deep(.p-dropdown-item:hover) {
    background: linear-gradient(to right, #eff6ff, #dbeafe);
}
</style>