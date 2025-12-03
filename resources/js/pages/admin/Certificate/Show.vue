<template>
    <AdminLayout title="Certificate">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-50 p-6">
            
            <!-- Back Button - Fixed Position -->
            <div class="max-w-5xl mx-auto mb-6 print-hide">
                <Button 
                    label="Back to Selection" 
                    icon="pi pi-arrow-left" 
                    severity="secondary"
                    text
                    class="!text-slate-600 hover:!bg-white hover:!shadow-md transition-all"
                    @click="router.visit('/admin/certificates')"
                />
            </div>

            <!-- Certificate Container -->
            <div class="certificate-container bg-white rounded-2xl shadow-2xl max-w-5xl mx-auto overflow-hidden border-4 border-slate-100">
                
                <!-- Certificate Header with Decorative Background -->
                <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 px-12 py-8 text-white">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.1) 10px, rgba(255,255,255,.1) 20px);"></div>
                    </div>
                    <div class="relative flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-white rounded-2xl p-2 shadow-xl">
                                <img src="/images/logo-polban.png" alt="Logo Polban" class="w-full h-full object-contain" />
                            </div>
                            <div>
                                <h1 class="text-3xl font-extrabold tracking-tight">POLITEKNIK NEGERI BANDUNG</h1>
                                <p class="text-blue-100 text-lg font-medium mt-1">UPA Bahasa (Language Service Unit)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Body -->
                <div class="px-16 py-12">
                    
                    <!-- Certificate Title -->
                    <div class="text-center mb-10">
                        <div class="inline-block">
                            <h2 class="text-4xl font-bold text-slate-800 mb-2 tracking-wide">CERTIFICATE</h2>
                            <div class="h-1 bg-gradient-to-r from-transparent via-blue-600 to-transparent"></div>
                        </div>
                        <p class="text-xl text-slate-600 font-semibold mt-4">English Proficiency Test</p>
                        <p class="text-sm text-slate-400 mt-1">Official Assessment Document</p>
                    </div>

                    <!-- Certificate Content -->
                    <div class="mb-10">
                        <p class="text-center text-slate-600 text-lg mb-8">This is to certify that</p>
                        
                        <!-- Student Information -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 mb-8 border-2 border-blue-100">
                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <label class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-2">Full Name</label>
                                    <p class="text-2xl font-bold text-slate-800">{{ mahasiswa.nama }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-2">Student ID Number</label>
                                    <p class="text-2xl font-bold text-slate-800">{{ mahasiswa.nim }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-2">Test Date</label>
                                    <p class="text-xl font-semibold text-slate-700">{{ formattedTestDate }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-bold text-slate-500 uppercase tracking-wider block mb-2">Certificate ID</label>
                                    <p class="text-xl font-semibold text-slate-700 font-mono">EPT-{{ mahasiswa.nim }}-2025</p>
                                </div>
                            </div>
                        </div>

                        <!-- Test Scores Section -->
                        <div class="mb-8">
                            <h3 class="text-center text-xl font-bold text-slate-700 mb-6 flex items-center justify-center gap-2">
                                <i class="pi pi-chart-bar text-blue-600"></i>
                                Test Score Breakdown
                            </h3>
                            
                            <div class="grid grid-cols-3 gap-6 mb-8">
                                <!-- Listening Score -->
                                <div class="bg-white border-2 border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition-all">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <i class="pi pi-volume-up text-white text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Listening</p>
                                    <p class="text-4xl font-extrabold text-blue-600">{{ eptScore.listening }}</p>
                                </div>

                                <!-- Structure Score -->
                                <div class="bg-white border-2 border-slate-200 rounded-xl p-6 text-center hover:border-indigo-400 transition-all">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center">
                                        <i class="pi pi-sitemap text-white text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Structure</p>
                                    <p class="text-4xl font-extrabold text-indigo-600">{{ eptScore.structure }}</p>
                                </div>

                                <!-- Reading Score -->
                                <div class="bg-white border-2 border-slate-200 rounded-xl p-6 text-center hover:border-purple-400 transition-all">
                                    <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <i class="pi pi-book text-white text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Reading</p>
                                    <p class="text-4xl font-extrabold text-purple-600">{{ eptScore.reading }}</p>
                                </div>
                            </div>

                            <!-- Total Score -->
                            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl p-8 text-center text-white shadow-xl">
                                <p class="text-sm font-bold uppercase tracking-wider mb-2 text-blue-100">Total EPT Score</p>
                                <p class="text-6xl font-extrabold mb-3">{{ eptScore.total_score }}</p>
                                <div class="inline-block bg-white/20 backdrop-blur-sm px-6 py-2 rounded-full">
                                    <p class="text-xl font-bold">{{ eptScore.level }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Signature Section -->
                    <div class="border-t-2 border-dashed border-slate-300 pt-10 mt-10">
                        <div class="flex justify-between items-end">
                            <div class="text-center">
                                <p class="text-sm text-slate-500 mb-1">Issued on:</p>
                                <p class="text-base font-bold text-slate-800">{{ formattedTestDate }}</p>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-sm text-slate-500 mb-16">Authorized Signature</p>
                                <div class="border-t-2 border-slate-800 w-64 mb-2"></div>
                                <p class="font-bold text-slate-800">Head of UPA Bahasa</p>
                                <p class="text-sm text-slate-500">Politeknik Negeri Bandung</p>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Footer -->
                    <div class="text-center mt-10 text-xs text-slate-400">
                        <p>This certificate is valid and verifiable through UPA Bahasa POLBAN</p>
                        <p class="mt-1">Certificate ID: EPT-{{ mahasiswa.nim }}-2025</p>
                    </div>

                </div>

            </div>

            <!-- Action Buttons -->
            <div class="max-w-5xl mx-auto mt-8 flex items-center justify-center gap-4 print-hide">
                <Button 
                    label="Back" 
                    icon="pi pi-arrow-left" 
                    severity="secondary"
                    outlined
                    class="!border-2 !border-slate-300 !text-slate-700 hover:!bg-slate-50"
                    @click="router.visit('/admin/certificate/')"
                />
                <Button 
                    label="Print Certificate" 
                    icon="pi pi-print" 
                    class="!bg-gradient-to-r !from-blue-600 !to-indigo-600 !border-none hover:!from-blue-700 hover:!to-indigo-700 !shadow-lg hover:!shadow-xl transition-all !px-8 !py-3"
                    @click="print"
                />
            </div>

        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Button from 'primevue/button'; // Assuming PrimeVue Button is used in the template

const props = defineProps<{
    mahasiswa: any; // More specific than Object
    eptScore: any; // More specific than Object
    testDate: string | null; // Explicitly allow null
}>();

const formattedTestDate = computed(() => {
    if (!props.testDate) return 'N/A';
    const date = new Date(props.testDate);
    // User requested: "yang diambil hanya bulan,tanggal, dan tahun."
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
});

const print = () => {
    window.print();
};
</script>

<style scoped>
@media print {
    body * {
        visibility: hidden;
    }
    .certificate-container, .certificate-container * {
        visibility: visible;
    }
    .certificate-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        border-radius: 0;
        border: none;
    }
    .print-hide {
        display: none !important;
    }
    
    /* Optimize for print */
    .certificate-container {
        page-break-inside: avoid;
    }
}

/* Animation for hover effects */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.certificate-container {
    animation: fadeIn 0.5s ease-out;
}
</style>