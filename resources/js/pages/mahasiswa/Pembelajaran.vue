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
        
        <!-- Hero Section -->
        <section class="relative w-full h-[350px] flex items-center justify-center overflow-hidden z-10 shadow-md">
            <div
                class="absolute inset-0 w-full h-full bg-cover bg-center z-0 animate-[pulse_15s_ease-in-out_infinite]"
                style="background-image: url('/banner-upa.jpeg');"
            ></div>
            <div class="absolute inset-0 bg-slate-900/50 z-10"></div>

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
            
            <!-- Filter Tabs - Centered -->
            <div class="flex justify-center mb-8 md:mb-12">
                <div class="bg-blue-50 p-1.5 rounded-xl shadow-lg border border-blue-100 inline-flex gap-1">
                    <button
                        @click="setFilter('Video')"
                        :class="{
                            'bg-blue-600 text-white shadow-md': filterJenis === 'Video', 
                            'text-slate-500 hover:text-blue-600 hover:bg-blue-100': filterJenis !== 'Video'
                        }"
                        class="py-2 px-4 md:px-5 rounded-lg font-bold transition-all duration-200 text-xs md:text-sm"
                    >
                        Video
                    </button>
                    
                    <button
                        @click="setFilter('PPT')"
                        :class="{
                            'bg-blue-600 text-white shadow-md': filterJenis === 'PPT', 
                            'text-slate-500 hover:text-blue-600 hover:bg-blue-100': filterJenis !== 'PPT'
                        }"
                        class="py-2 px-4 md:px-5 rounded-lg font-bold transition-all duration-200 text-xs md:text-sm"
                    >
                        PPT
                    </button>
                    
                    <button
                        @click="setFilter('PDF')"
                        :class="{
                            'bg-blue-600 text-white shadow-md': filterJenis === 'PDF', 
                            'text-slate-500 hover:text-blue-600 hover:bg-blue-100': filterJenis !== 'PDF'
                        }"
                        class="py-2 px-4 md:px-5 rounded-lg font-bold transition-all duration-200 text-xs md:text-sm"
                    >
                        PDF
                    </button>
                </div>
            </div>
            
            <!-- Cards Grid - Horizontal Scroll on Mobile -->
            <div class="overflow-x-auto pb-4 -mx-4 px-4 md:overflow-visible">
                <div class="flex md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-8">
                    <div 
                        v-for="materi in filteredMateris" 
                        :key="materi.id" 
                        class="w-[calc(33.333%-10.67px)] min-w-[calc(33.333%-10.67px)] md:w-auto md:min-w-0 flex-shrink-0 bg-white rounded-2xl shadow-lg overflow-hidden transition duration-300 border border-gray-100 flex flex-col"
                        :class="{'cursor-pointer hover:shadow-xl hover:-translate-y-2': filterJenis !== 'Video'}" 
                        @click="filterJenis !== 'Video' && openModal(materi)"
                    >
                        <div class="h-32 md:h-48 flex items-center justify-center relative"
                             :class="filterJenis === 'Video' ? 'bg-gray-900' : (filterJenis === 'PDF' ? 'bg-gradient-to-br from-red-100 via-red-200 to-red-300' : 'bg-gradient-to-br from-orange-100 via-orange-200 to-yellow-200')">
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
                                    <div v-else class="text-gray-500 text-center p-2 md:p-4 w-full h-full flex flex-col justify-center items-center">
                                        <svg class="w-8 h-8 md:w-12 md:h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                        <p class="text-[10px] md:text-xs text-white mt-1 md:mt-2">Video tidak dapat dimuat</p>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 text-center p-2 md:p-4 w-full h-full flex flex-col justify-center items-center">
                                    <svg class="w-8 h-8 md:w-12 md:h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                    <p class="text-[10px] md:text-xs text-white mt-1 md:mt-2">Video tidak tersedia</p>
                                </div>
                            </template>
                            <template v-else>
                                <div class="relative w-full h-full flex flex-col justify-center items-center overflow-hidden">
                                    <!-- Pattern Background -->
                                    <div class="absolute inset-0 opacity-10">
                                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                                    <circle cx="2" cy="2" r="1" fill="currentColor" class="text-gray-400"/>
                                                </pattern>
                                            </defs>
                                            <rect width="100%" height="100%" fill="url(#dots)"/>
                                        </svg>
                                    </div>
                                    
                                    <!-- Document Illustration - Scaled for mobile -->
                                    <div class="relative z-10">
                                        <div class="relative">
                                            <!-- Back paper -->
                                            <div class="absolute -top-1 -right-1 md:-top-2 md:-right-2 w-12 h-16 md:w-20 md:h-24 bg-gray-700 rounded-sm transform rotate-3"></div>
                                            <!-- Middle paper -->
                                            <div class="absolute -top-0.5 -right-0.5 md:-top-1 md:-right-1 w-12 h-16 md:w-20 md:h-24 bg-gray-600 rounded-sm transform rotate-2"></div>
                                            <!-- Front paper -->
                                            <div class="relative w-12 h-16 md:w-20 md:h-24 bg-white rounded-sm shadow-lg p-1 md:p-2 flex flex-col">
                                                <!-- Document lines -->
                                                <div class="space-y-0.5 md:space-y-1.5 mb-auto">
                                                    <div class="h-0.5 md:h-1 bg-gray-300 rounded w-full"></div>
                                                    <div class="h-0.5 md:h-1 bg-gray-300 rounded w-4/5"></div>
                                                    <div class="h-0.5 md:h-1 bg-gray-300 rounded w-full"></div>
                                                    <div class="h-0.5 md:h-1 bg-gray-300 rounded w-3/5"></div>
                                                </div>
                                                <!-- File type badge -->
                                                <div class="mt-auto">
                                                    <span :class="filterJenis === 'PPT' ? 'bg-orange-500' : 'bg-red-500'" 
                                                          class="text-white text-[6px] md:text-[8px] font-bold px-1 md:px-1.5 py-0.5 rounded">
                                                        {{ filterJenis }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-[10px] md:text-xs text-gray-400 mt-2 md:mt-4 relative z-10">Dokumen Materi</p>
                                </div>
                            </template>
                        </div>
                        
                        <div class="p-3 md:p-4 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-xs md:text-base font-bold mb-1 line-clamp-2">{{ materi.judul }}</h3>
                                <p class="text-[10px] md:text-xs text-gray-500 mb-1 md:mb-2 line-clamp-1">
                                    {{ filterJenis === 'PPT' ? 'Presentasi' : 'Dokumen PDF' }}
                                </p>
                                <p class="text-xs md:text-sm text-gray-600 line-clamp-2 mb-2 md:mb-3">{{ materi.deskripsi_singkat }}</p>
                            </div>

                            <div class="mt-2">
                                <a 
                                    v-if="filterJenis === 'Video' && materi.link_video"
                                    :href="materi.link_video" 
                                    target="_blank" 
                                    class="inline-flex items-center justify-center w-full py-1.5 md:py-2 text-[10px] md:text-sm font-medium rounded-md bg-red-600 text-white hover:bg-red-700 transition duration-200"
                                    @click.stop 
                                >
                                    <svg class="w-3 h-3 md:w-5 md:h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                    <span class="hidden sm:inline">Tonton di YouTube</span>
                                    <span class="sm:hidden">YouTube</span>
                                </a>
                                
                                <button
                                    v-else-if="filterJenis !== 'Video' && materi.link_pdf"
                                    @click.stop="openModal(materi)" 
                                    class="inline-flex items-center justify-center w-full py-1.5 md:py-2 text-[10px] md:text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition duration-200"
                                >
                                    <svg class="w-3 h-3 md:w-4 md:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    <span class="hidden sm:inline">Klik untuk Detail & Download</span>
                                    <span class="sm:hidden">Detail</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="filteredMateris.length === 0" class="col-span-full text-center py-10 text-gray-500 text-sm md:text-base">
                        Tidak ada materi {{ filterJenis === 'Video' ? 'Video' : filterJenis }} yang tersedia saat ini.
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex justify-center items-center p-4" @click.self="closeModal">
            <div class="bg-white rounded-lg w-full max-w-sm shadow-2xl transform transition-all duration-300 scale-100" @click.stop>
                
                <div class="flex justify-end p-3">
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="px-4 md:px-6 pb-4 md:pb-6 text-center">
                    <p class="font-bold text-base md:text-lg mb-4 text-gray-800">{{ selectedMateri?.judul }}</p>
                    
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 h-40 md:h-48 mb-4 flex items-center justify-center rounded-lg mx-auto w-11/12 relative overflow-hidden">
                        <!-- Decorative background pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="modal-grid" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                                        <rect x="0" y="0" width="1" height="30" fill="currentColor" class="text-gray-400"/>
                                        <rect x="0" y="0" width="30" height="1" fill="currentColor" class="text-gray-400"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#modal-grid)"/>
                            </svg>
                        </div>

                        <!-- Document Illustration for Modal -->
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="relative">
                                <!-- Shadow documents behind -->
                                <div class="absolute -top-2 -left-2 md:-top-3 md:-left-3 w-20 h-28 md:w-28 md:h-36 rounded-lg transform -rotate-6 opacity-40"
                                     :class="selectedMateri?.file_type === 'pdf' ? 'bg-red-400' : 'bg-orange-400'"></div>
                                <div class="absolute -top-1 -right-1 md:-top-2 md:-right-2 w-20 h-28 md:w-28 md:h-36 rounded-lg transform rotate-6 opacity-40"
                                     :class="selectedMateri?.file_type === 'pdf' ? 'bg-red-400' : 'bg-orange-400'"></div>
                                
                                <!-- Main document -->
                                <div class="relative w-20 h-28 md:w-28 md:h-36 bg-white rounded-lg shadow-2xl border-2 border-gray-200 p-2 md:p-3 flex flex-col">
                                    <!-- Header with icon -->
                                    <div class="flex items-center justify-between mb-1 md:mb-2">
                                        <div class="w-5 h-5 md:w-6 md:h-6 rounded flex items-center justify-center"
                                             :class="selectedMateri?.file_type === 'pdf' ? 'bg-red-100' : 'bg-orange-100'">
                                            <!-- PDF Icon -->
                                            <svg v-if="selectedMateri?.file_type === 'pdf'" class="w-3 h-3 md:w-4 md:h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                            </svg>
                                            <!-- PPT Icon -->
                                            <svg v-else class="w-3 h-3 md:w-4 md:h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-[8px] md:text-[10px] font-bold px-1 md:px-1.5 py-0.5 rounded"
                                              :class="selectedMateri?.file_type === 'pdf' ? 'bg-red-500 text-white' : 'bg-orange-500 text-white'">
                                            {{ selectedMateri?.file_type?.toUpperCase() }}
                                        </span>
                                    </div>
                                    
                                    <!-- Document content lines -->
                                    <div class="space-y-1 md:space-y-2 flex-1">
                                        <div class="h-1 md:h-1.5 bg-gray-200 rounded w-full"></div>
                                        <div class="h-1 md:h-1.5 bg-gray-200 rounded w-5/6"></div>
                                        <div class="h-1 md:h-1.5 bg-gray-200 rounded w-full"></div>
                                        <div class="h-1 md:h-1.5 bg-gray-200 rounded w-4/6"></div>
                                        <div class="h-1 md:h-1.5 bg-gray-200 rounded w-full"></div>
                                        <div class="h-1 md:h-1.5 bg-gray-200 rounded w-3/4"></div>
                                    </div>
                                    
                                    <!-- Page number -->
                                    <div class="mt-1 md:mt-2 text-center">
                                        <span class="text-[7px] md:text-[8px] text-gray-400 font-medium">Page 1</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- File info badge -->
                            <div class="mt-2 md:mt-4 px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-medium"
                                 :class="selectedMateri?.file_type === 'pdf' ? 'bg-red-50 text-red-700' : 'bg-orange-50 text-orange-700'">
                                {{ selectedMateri?.file_type === 'pdf' ? 'Dokumen PDF' : 'Presentasi' }}
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-xs md:text-sm text-gray-600 mb-4 md:mb-6 text-left px-2 md:px-4">
                        {{ selectedMateri?.deskripsi_singkat }}
                    </p>
                    
                    <a 
                        v-if="selectedMateri?.link_pdf_url"
                        :href="selectedMateri?.link_pdf_url" 
                        target="_blank" 
                        class="inline-flex items-center bg-green-500 text-white font-bold py-2 px-6 md:px-8 rounded-lg hover:bg-green-600 transition duration-200 shadow-md text-sm md:text-base"
                        @click="closeModal"
                    >
                        Download
                    </a>
                    <p v-else class="text-xs md:text-sm text-gray-500">File tidak tersedia untuk diunduh.</p>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<style scoped>
/* Custom scrollbar untuk horizontal scroll di mobile */
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>