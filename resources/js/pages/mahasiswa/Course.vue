<script setup lang="ts">
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

const props = defineProps({
    courses: Array
});

// filter
const selected = ref('Semua');
const categories = ['Semua', 'Inggris', 'Korea', 'Jepang'];

const filteredCourses = computed(() => {
    if (selected.value === 'Semua') return props.courses;
    return props.courses.filter(c => c.jenis === selected.value);
});

// modal
const show = ref(false);
const selectedCourse = ref(null);

const openModal = (course) => {
    selectedCourse.value = course;
    show.value = true;
};

// WhatsApp redirect
const contactWhatsApp = () => {
    const phoneNumber = '6281234567890'; // Ganti dengan nomor WA Anda (format: 62xxx tanpa +)
    const message = `Halo, saya ingin mendaftar kursus *${selectedCourse.value.judul}* (${selectedCourse.value.jenis})`;
    const url = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
};

// Email redirect
const contactEmail = () => {
    const email = 'info@upabahasa.com'; // Ganti dengan email Anda
    const subject = `Pendaftaran Kursus ${selectedCourse.value.judul}`;
    const body = `Halo,\n\nSaya ingin mendaftar kursus ${selectedCourse.value.judul} (${selectedCourse.value.jenis}).\n\nMohon informasi lebih lanjut.\n\nTerima kasih.`;
    const url = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = url;
};
</script>

<template>
<Head title="Kursus" />
<Layout>

    <!-- Header - Full width tanpa gap -->
    <section class="bg-[#0459A0] text-white py-10 text-center w-screen relative left-1/2 right-1/2 -mx-[50vw]">
        <h1 class="text-2xl md:text-3xl font-bold">Program Kursus Bahasa</h1>
        <p class="mt-1 text-sm md:text-base opacity-90">
            Pilih program kursus bahasa yang sesuai dengan kebutuhan Anda
        </p>
    </section>

    <!-- Filter - Lebih compact -->
    <div class="flex justify-center mt-6 gap-2 px-4">
        <button
            v-for="cat in categories"
            :key="cat"
            @click="selected = cat"
            class="px-4 py-1.5 rounded-full border text-sm transition"
            :class="selected === cat 
                ? 'bg-[#0467C6] text-white border-[#0467C6]' 
                : 'border-[#0467C6] text-[#0467C6]'"
        >
            {{ cat }}
        </button>
    </div>

    <!-- Course Grid - Layout lebih padat -->
    <div class="mt-6 mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
            v-for="course in filteredCourses"
            :key="course.id"
            class="rounded-lg shadow hover:shadow-lg transition overflow-hidden bg-white flex flex-col"
        >
            <!-- Image lebih kecil -->
            <img :src="course.image" class="h-36 w-full object-cover" />

            <div class="p-4 flex flex-col flex-1">
                <Tag
                    :value="course.jenis"
                    :severity="course.jenis === 'Inggris' ? 'warning' : course.jenis === 'Korea' ? 'info' : 'danger'"
                    class="text-xs mb-2 w-fit"
                />

                <h3 class="font-bold text-base mb-2">{{ course.judul }}</h3>

                <p class="text-xs text-gray-600 mb-4 line-clamp-2 flex-1">
                    {{ course.deskripsi }}
                </p>

                <Button 
                    label="Lihat Detail"
                    size="small"
                    class="w-full border-none text-white rounded-md text-sm py-2"
                    style="background-color: #ff8c42 !important;"
                    @click="openModal(course)"
                />
            </div>
        </div>
    </div>

    <!-- Modal Detail - Fixed scrolling issue -->
    <Dialog
        v-model:visible="show"
        modal
        :draggable="false"
        :dismissableMask="true"
        :style="{ width: '90vw', maxWidth: '550px' }"
        :contentStyle="{ padding: 0, maxHeight: '80vh', overflow: 'hidden' }"
        v-if="selectedCourse"
    >
        <template #container="{ closeCallback }">
            <div class="flex flex-col bg-white rounded-lg overflow-hidden" style="max-height: 80vh;">
                <!-- Image header tetap -->
                <div class="relative">
                    <img 
                        :src="selectedCourse.image" 
                        class="w-full h-40 object-cover"
                    />
                    <button 
                        @click="closeCallback"
                        class="absolute top-3 right-3 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition"
                    >
                        <i class="pi pi-times text-gray-700"></i>
                    </button>
                </div>

                <!-- Scrollable content -->
                <div class="overflow-y-auto flex-1 p-5" style="max-height: calc(80vh - 160px);">
                    <h2 class="text-xl font-bold mb-3">
                        {{ selectedCourse.judul }}
                    </h2>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-[#0459A0] flex items-center gap-2 text-sm mb-1">
                            <i class="pi pi-book text-xs"></i> Deskripsi Kursus
                        </h3>
                        <p class="text-sm text-gray-700">
                            {{ selectedCourse.deskripsi }}
                        </p>
                    </div>

                    <!-- Ketentuan -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-[#0459A0] flex items-center gap-2 text-sm mb-1">
                            <i class="pi pi-info-circle text-xs"></i> Ketentuan Pendaftaran
                        </h3>
                        <p class="text-sm whitespace-pre-line text-gray-700">
                            {{ selectedCourse.ketentuan }}
                        </p>
                    </div>

                    <!-- Sistem Pembelajaran -->
                    <div class="mb-4">
                        <h3 class="font-semibold text-[#0459A0] flex items-center gap-2 text-sm mb-1">
                            <i class="pi pi-desktop text-xs"></i> Sistem Pembelajaran
                        </h3>
                        <p class="text-sm text-gray-700">
                            {{ selectedCourse.sistem_pembelajaran }}
                        </p>
                        <p class="text-sm mt-1 text-gray-700">
                            <b>Jadwal:</b> {{ selectedCourse.jadwal }}
                        </p>
                    </div>

                    <!-- Kontak -->
                    <div class="pb-2">
                        <h3 class="font-semibold text-[#0459A0] flex items-center gap-2 text-sm mb-2">
                            <i class="pi pi-phone text-xs"></i> Kontak Pendaftaran
                        </h3>

                        <div class="flex gap-2">
                            <Button 
                                label="WhatsApp"
                                icon="pi pi-whatsapp"
                                size="small"
                                class="bg-green-500 border-none flex-1 text-sm hover:bg-green-600"
                                @click="contactWhatsApp"
                            />
                            <Button 
                                label="Email"
                                icon="pi pi-envelope"
                                size="small"
                                class="bg-orange-500 border-none flex-1 text-sm hover:bg-orange-600"
                                @click="contactEmail"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Dialog>

</Layout>
</template>

<style scoped>
/* Text clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar untuk modal */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #0459A0;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #034880;
}

/* Hilangkan gap di tepi halaman */
:deep(.p-dialog-mask) {
    backdrop-filter: blur(2px);
}
</style>