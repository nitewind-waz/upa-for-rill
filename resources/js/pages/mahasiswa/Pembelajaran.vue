<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '../../layouts/AppLayout.vue'; 
import getYouTubeID from 'get-youtube-id';

// Fungsi untuk embed URL YouTube
const getYouTubeEmbedUrl = (url) => {
    if (!url) return '';
    const videoId = getYouTubeID(url);
    return videoId ? `https://www.youtube.com/embed/${videoId}?autoplay=0&controls=1&showinfo=0&rel=0` : '';
};

const props = defineProps({
    materis: {
        type: Array,
        default: () => []
    }
});


const filterJenis = ref('Video'); 

// State untuk Modal Pratinjau/Unduh
const showModal = ref(false);
const selectedMateri = ref(null);


const setFilter = (jenis) => {
    filterJenis.value = jenis; 
};

const filteredMateris = computed(() => {
    return props.materis.filter(materi => {
        if (filterJenis.value === 'Video') {
            return materi.link_video; 
        } 
        
        if (filterJenis.value === 'PPT') {
            return materi.link_pdf && (materi.file_type === 'ppt' || materi.file_type === 'pptx');
        }

        if (filterJenis.value === 'PDF') {
            return materi.link_pdf && materi.file_type === 'pdf'; 
        }
        return false;
    });
});

const openModal = (materi) => {
    selectedMateri.value = materi;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedMateri.value = null;
};
</script>

<template>
    <AppLayout title="Pembelajaran">
        <Head title="Pembelajaran" />
        
            <section class="relative w-full h-[350px] flex items-center justify-center overflow-hidden z-10 shadow-md">
                <div
                    class="absolute inset-0 w-full h-full bg-cover bg-center z-0 animate-[pulse_15s_ease-in-out_infinite]"
                    style="background-image: url('/banner-upa.jpeg');"
                ></div>
                <div class="absolute inset-0 bg-blue-900/60 z-10"></div> <!-- Darker blue overlay -->
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-slate-50 z-20"></div> <!-- Fade to white at bottom -->


                <div class="relative z-30 container mx-auto px-6 text-center flex flex-col justify-center h-full pt-4">
                    <span class="inline-block py-1 px-2 rounded-full bg-blue-600/30 border border-blue-400/50 text-blue-50 text-xs font-bold mb-4 backdrop-blur-md uppercase tracking-widest">
                        Materi Belajar
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight drop-shadow-lg mb-3">
                        Pembelajaran Bahasa
                    </h1>
                    <p class="text-base md:text-lg text-white/90 max-w-2xl mx-auto mb-6 font-medium leading-relaxed drop-shadow-md">
                        Akses berbagai jenis materi pembelajaran untuk meningkatkan kemampuan bahasa Anda.
                    </p>
                </div>
            </section>
        <div class="container mx-auto p-4 md:p-8">
            
            <div class="flex justify-center space-x-2 mb-12">
                
                <button
                    @click="setFilter('Video')"
                    :class="{
                        'bg-blue-600 text-white': filterJenis === 'Video', 
                        'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50': filterJenis !== 'Video'
                    }"
                    class="py-2 px-4 rounded-lg font-semibold transition duration-200 text-sm"
                >
                    Video
                </button>
                
                <button
                    @click="setFilter('PPT')"
                    :class="{
                        'bg-blue-600 text-white': filterJenis === 'PPT', 
                        'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50': filterJenis !== 'PPT'
                    }"
                    class="py-2 px-4 rounded-lg font-semibold transition duration-200 text-sm"
                >
                    PPT
                </button>
                
                <button
                    @click="setFilter('PDF')"
                    :class="{
                        'bg-blue-600 text-white': filterJenis === 'PDF', 
                        'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50': filterJenis !== 'PDF'
                    }"
                    class="py-2 px-4 rounded-lg font-semibold transition duration-200 text-sm"
                >
                    PDF
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <div 
                    v-for="materi in filteredMateris" 
                    :key="materi.id" 
                    class="bg-white rounded-lg shadow-lg overflow-hidden transition duration-300 border border-gray-100"
                    :class="{'cursor-pointer hover:shadow-xl': filterJenis !== 'Video'}" 
                    @click="filterJenis !== 'Video' && openModal(materi)"
                >
                    <div class="h-48 bg-gray-900 flex items-center justify-center relative">
                        <template v-if="filterJenis === 'Video'">
                            <div v-if="materi.link_video" class="w-full h-full">
                                <iframe 
                                    v-if="getYouTubeEmbedUrl(materi.link_video)"
                                    :src="getYouTubeEmbedUrl(materi.link_video)" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="w-full h-full"
                                ></iframe>
                                <div v-else class="text-gray-500 text-center p-4 w-full h-full flex flex-col justify-center items-center">
                                    <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                    <p class="text-xs text-white mt-2">Video tidak dapat dimuat</p>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-center p-4 w-full h-full flex flex-col justify-center items-center">
                                <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                <p class="text-xs text-white mt-2">Video tidak tersedia</p>
                            </div>
                        </template>
                        <template v-else>
                            <div class="text-gray-500 text-center p-4 w-full h-full flex flex-col justify-center items-center">
                                <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-4v2a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v10a2 2 0 002 2h4a2 2 0 002-2v-2"></path></svg>
                                <p class="text-xs text-white">Dokumen Materi</p>
                            </div>
                        </template>
                    </div>
                    
                    <div class="p-4 flex flex-col justify-between h-auto">
                        <div>
                            <h3 class="text-base font-bold mb-1 line-clamp-2">{{ materi.judul }}</h3>
                            <p class="text-xs text-gray-500 mb-2 line-clamp-1">
                                {{ filterJenis === 'PPT' ? 'Presentasi' : 'Dokumen PDF' }}
                            </p>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ materi.deskripsi_singkat }}</p>
                        </div>

                        <div class="mt-2">
                            <a 
                                v-if="filterJenis === 'Video' && materi.link_video"
                                :href="materi.link_video" 
                                target="_blank" 
                                class="inline-flex items-center justify-center w-full py-2 text-sm font-medium rounded-md bg-red-600 text-white hover:bg-red-700 transition duration-200"
                                @click.stop 
                            >
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                Tonton di YouTube
                            </a>
                            
                            <button
                                v-else-if="filterJenis !== 'Video' && materi.link_pdf"
                                @click.stop="openModal(materi)" 
                                class="inline-flex items-center justify-center w-full py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition duration-200"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Klik untuk Detail & Download
                            </button>
                        </div>
                    </div>
                </div>
                
                <div v-if="filteredMateris.length === 0" class="col-span-full text-center py-10 text-gray-500">
                    Tidak ada materi {{ filterJenis === 'Video' ? 'Video' : filterJenis }} yang tersedia saat ini.
                </div>
            </div>
            
        </div>
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex justify-center items-center p-4" @click.self="closeModal">
            <div class="bg-white rounded-lg w-full max-w-sm shadow-2xl transform transition-all duration-300 scale-100" @click.stop>
                
                <div class="flex justify-end p-3">
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="px-6 pb-6 text-center">
                    <p class="font-bold text-lg mb-4 text-gray-800">{{ selectedMateri?.judul }}</p>
                    
                    <div class="bg-white border border-gray-300 h-40 mb-4 flex flex-col items-start justify-start p-3 text-gray-500 rounded-md mx-auto w-11/12">
                        <p class="text-sm font-medium">Preview</p>
                        <p v-if="selectedMateri?.link_pdf_url" class="text-xs text-gray-400 mt-1">
                            {{ selectedMateri?.link_pdf_url.endsWith('.pdf') ? 'Dokumen PDF tersedia' : 'Dokumen Presentasi tersedia' }}
                        </p>
                        <p v-else class="text-xs text-gray-400 mt-1">Tidak ada pratinjau</p>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6 text-left px-4">
                        {{ selectedMateri?.deskripsi_singkat }}
                    </p>
                    
                    <a 
                        v-if="selectedMateri?.link_pdf_url"
                        :href="selectedMateri?.link_pdf_url" 
                        target="_blank" 
                        class="inline-flex items-center bg-green-500 text-white font-bold py-2 px-8 rounded-lg hover:bg-green-600 transition duration-200 shadow-md"
                        @click="closeModal"
                    >
                        Download
                    </a>
                    <p v-else class="text-sm text-gray-500">File tidak tersedia untuk diunduh.</p>
                </div>
            </div>
        </div>

    </AppLayout>
</template>