<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

const props = defineProps({
    courses: {
        type: Array,
        default: () => []
    }
});

// --- LOGIC DINAMIS ---

// 1. Ambil Kategori Unik
const categories = computed(() => {
    const uniqueTypes = [...new Set(props.courses.map(c => c.jenis))];
    return ['Semua', ...uniqueTypes.sort()];
});

const selectedFilter = ref('Semua');

// 2. Filter Logic
const filteredCourses = computed(() => {
    if (selectedFilter.value === 'Semua') return props.courses;
    return props.courses.filter(c => c.jenis === selectedFilter.value);
});

// 3. Helper Gambar
const getImageUrl = (path) => {
    return path ? `/storage/${path}` : 'https://via.placeholder.com/400x300?text=No+Image';
};

// --- MODAL & ACTION ---
const showModal = ref(false);
const selectedCourse = ref(null);

const openModal = (course) => {
    selectedCourse.value = course;
    showModal.value = true;
};

const contactWhatsApp = () => {
    if (!selectedCourse.value) return;
    const phoneNumber = '6281234567890'; 
    const message = `Halo Admin UPA Bahasa, saya tertarik mendaftar kursus *${selectedCourse.value.judul}*. Mohon infonya.`;
    window.open(`https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`, '_blank');
};

const contactEmail = () => {
    if (!selectedCourse.value) return;
    const email = 'upa.bahasa@polban.ac.id';
    const subject = `Pendaftaran Kursus: ${selectedCourse.value.judul}`;
    const body = `Halo,\n\nSaya berminat mendaftar kursus ${selectedCourse.value.judul} (${selectedCourse.value.jenis}).\nMohon informasi lebih lanjut.\n\nTerima kasih.`;
    window.location.href = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
};

// Helper warna tag
const getTagSeverity = (jenis) => {
    const map = {
        'Gratis': 'success',
        'Berbayar': 'info',
        'Inggris': 'info',
        'Korea': 'danger',
        'Jepang': 'warning',
        'Mandarin': 'success'
    };
    return map[jenis] || 'secondary';
};
</script>

<template>
    <Head title="Program Kursus" />
    
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
                        Layanan Akademik
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight drop-shadow-lg mb-3">
                        Program Kursus Bahasa
                    </h1>
                    <p class="text-white/90 text-lg max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
                        Tingkatkan kompetensi bahasa asing Anda dengan kurikulum standar industri dan pengajar profesional.
                    </p>
                </div>
            </section>

            <section class="relative pb-20 -mt-10 z-30">
                <div class="container mx-auto px-6">
                    
                    <div class="flex justify-center mb-10">
                        <div class="bg-blue-50 p-1.5 rounded-xl shadow-lg border border-blue-100 inline-flex flex-wrap justify-center gap-1">
                            <button
                                v-for="cat in categories"
                                :key="cat"
                                @click="selectedFilter = cat"
                                class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-200"
                                :class="selectedFilter === cat 
                                    ? 'bg-blue-600 text-white shadow-md transform scale-105' 
                                    : 'text-slate-500 hover:text-blue-600 hover:bg-blue-100'"
                            >
                                {{ cat }}
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            v-for="course in filteredCourses"
                            :key="course.id"
                            class="group bg-blue-50 rounded-2xl border border-blue-100 overflow-hidden hover:shadow-xl hover:shadow-blue-200/50 transition-all duration-300 hover:-translate-y-2 flex flex-col h-full"
                        >
                            <div class="relative h-52 overflow-hidden flex-shrink-0">
                                <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-colors z-10"></div>
                                <img 
                                    :src="getImageUrl(course.images)" 
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                                />
                                <div class="absolute top-4 left-4 z-20">
                                    <Tag 
                                        :value="course.jenis" 
                                        :severity="getTagSeverity(course.jenis)"
                                        class="!text-xs !font-bold shadow-sm"
                                    />
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ course.judul }}
                                </h3>
                                
                                <div class="flex-1">
                                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-6">
                                        {{ course.deskripsi }}
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-blue-200 mt-auto">
                                    <Button 
                                        label="Lihat Detail & Daftar"
                                        icon="pi pi-arrow-right"
                                        iconPos="right"
                                        class="w-full !bg-white !text-blue-600 !border-blue-200 hover:!bg-blue-100 hover:!border-blue-300 !font-bold"
                                        outlined
                                        rounded
                                        @click="openModal(course)"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="filteredCourses.length === 0" class="text-center py-16 bg-blue-50 rounded-2xl border-2 border-dashed border-blue-200">
                        <div class="inline-flex p-4 rounded-full bg-blue-100 mb-3">
                            <i class="pi pi-search text-2xl text-blue-400"></i>
                        </div>
                        <p class="text-slate-500 font-medium">Tidak ada kursus ditemukan untuk kategori "{{ selectedFilter }}".</p>
                    </div>

                </div>
            </section>

            <Dialog
                v-model:visible="showModal"
                modal
                :draggable="false"
                :dismissableMask="true"
                :style="{ width: '90vw', maxWidth: '600px' }"
                :contentStyle="{ padding: 0 }"
                :showHeader="false" 
                class="custom-modal"
            >
                <div v-if="selectedCourse" class="flex flex-col bg-white overflow-hidden rounded-lg">
                    <div class="relative h-48">
                        <img 
                            :src="getImageUrl(selectedCourse.images)" 
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        
                        <button 
                            @click="showModal = false"
                            class="absolute top-4 right-4 w-8 h-8 bg-black/30 hover:bg-black/50 backdrop-blur rounded-full flex items-center justify-center text-white transition-colors focus:outline-none"
                        >
                            <i class="pi pi-times"></i>
                        </button>

                        <div class="absolute bottom-4 left-6 text-white">
                            <Tag :value="selectedCourse.jenis" :severity="getTagSeverity(selectedCourse.jenis)" class="mb-2 shadow-sm" />
                            <h2 class="text-2xl font-bold shadow-black drop-shadow-md">{{ selectedCourse.judul }}</h2>
                        </div>
                    </div>

                    <div class="p-6 overflow-y-auto max-h-[60vh] space-y-6">
                        
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="pi pi-align-left text-blue-600"></i> Deskripsi
                            </h4>
                            <p class="text-slate-600 text-sm leading-relaxed text-justify">
                                {{ selectedCourse.deskripsi }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <div>
                                <h4 class="text-xs font-bold text-slate-500 uppercase mb-1">Jadwal</h4>
                                <p class="text-slate-800 text-sm font-medium flex items-start gap-2">
                                    <i class="pi pi-calendar text-blue-500 mt-0.5"></i> 
                                    {{ selectedCourse.jadwal || 'Jadwal menyusul' }}
                                </p>
                            </div>
                             <div>
                                <h4 class="text-xs font-bold text-slate-500 uppercase mb-1">Metode</h4>
                                <p class="text-slate-800 text-sm font-medium flex items-start gap-2">
                                    <i class="pi pi-desktop text-blue-500 mt-0.5"></i> 
                                    {{ selectedCourse.sistem_pembelajaran || 'Offline / Online' }}
                                </p>
                            </div>
                        </div>

                        <div v-if="selectedCourse.ketentuan">
                            <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                                <i class="pi pi-info-circle text-blue-600"></i> Ketentuan
                            </h4>
                            <div class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                                {{ selectedCourse.ketentuan }}
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-100 bg-white flex gap-3">
                        <Button 
                            label="WhatsApp"
                            icon="pi pi-whatsapp"
                            class="flex-1 !bg-green-600 !border-green-600 hover:!bg-green-700"
                            size="large"
                            @click="contactWhatsApp"
                        />
                        <Button 
                            label="Email"
                            icon="pi pi-envelope"
                            class="flex-1 !bg-slate-800 !border-slate-800 hover:!bg-slate-900"
                            size="large"
                            @click="contactEmail"
                        />
                    </div>
                </div>
            </Dialog>

        </div>
    </Layout>
</template>

<style scoped>
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
::-webkit-scrollbar-track {
    background: #f1f5f9;
}
::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

:deep(.p-dialog-content) {
    padding: 0 !important;
}
</style>