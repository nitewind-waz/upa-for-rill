<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Checkbox from 'primevue/checkbox';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Admin Login" />

    <div class="min-h-screen flex items-center justify-center bg-slate-50 relative overflow-hidden font-sans">
        
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:30px_30px] opacity-[0.2]"></div>
            <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-blue-200/40 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-5%] right-[-5%] w-[500px] h-[500px] bg-orange-100/50 rounded-full blur-[100px] animate-pulse delay-1000"></div>
        </div>

        <div class="relative z-10 w-full max-w-md p-6">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center bg-white p-3 rounded-2xl shadow-lg mb-4">
                    <img src="/images/logo-polban.png" alt="Logo Polban" class="h-12 w-auto object-contain" />
                </div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    UPA BAHASA <span class="text-blue-600">POLBAN</span>
                </h1>
                <p class="text-slate-500 text-sm mt-1">Administrator Portal Access</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl shadow-2xl p-8">
                
                <div class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-slate-800">Selamat Datang Kembali</h2>
                    <p class="text-sm text-slate-500">Silakan masuk untuk mengelola data.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    
                    <div class="space-y-1.5">
                        <label for="email" class="text-sm font-semibold text-slate-600 ml-1">Email Address</label>

                        <span class="p-input-icon-left w-full block">
                            <i class="pi pi-envelope text-slate-400"></i>
                            <InputText 
                                id="email"
                                v-model="form.email"
                                placeholder="Email address."
                                class="w-full !rounded-xl !border-slate-200 
                                    focus:!border-blue-500 focus:!ring-2 focus:!ring-blue-100 transition-all"
                                :class="{ 'p-invalid': form.errors.email }"
                            />
                        </span>

                        <small class="text-red-500 text-xs ml-1" v-if="form.errors.email">{{ form.errors.email }}</small>
                    </div>


                    <div class="space-y-1.5">
                        <label for="password" class="text-sm font-semibold text-slate-600 ml-1">Password</label>
                        <div class="relative">
                            <i class="pi pi-lock text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 z-10"></i>
                            <Password 
                                id="password" 
                                v-model="form.password" 
                                placeholder="Password"
                                :feedback="false"
                                toggleMask
                                inputClass="w-full !pl-10 !rounded-xl !border-slate-200 focus:!border-blue-500 focus:!ring-2 focus:!ring-blue-100 transition-all"
                                class="w-full"
                                :class="{ 'p-invalid': form.errors.password }"
                            />
                        </div>
                        <small class="text-red-500 text-xs ml-1" v-if="form.errors.password">{{ form.errors.password }}</small>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Checkbox v-model="form.remember" :binary="true" inputId="remember" />
                            <label for="remember" class="text-sm text-slate-600 cursor-pointer select-none">Ingat saya</label>
                        </div>
                        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">Lupa password?</a>
                    </div>

                    <Button 
                        type="submit" 
                        label="Masuk ke Dashboard" 
                        icon="pi pi-sign-in" 
                        class="w-full !bg-blue-600 !border-blue-600 hover:!bg-blue-700 !rounded-xl !py-3 !font-bold shadow-lg shadow-blue-200 transition-all hover:-translate-y-0.5"
                        :loading="form.processing"
                    />

                </form>
            </div>

            <p class="text-center text-xs text-slate-400 mt-8">
                &copy; 2025 UPA Bahasa Politeknik Negeri Bandung.
            </p>

        </div>
    </div>
</template>

<style scoped>
/* Custom Styling untuk Icon di dalam InputText PrimeVue */
:deep(.p-input-icon-left) {
    position: relative;
    display: block;
}

:deep(.p-input-icon-left > i) {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1;
}

:deep(.p-input-icon-left > .p-inputtext) {
    padding-left: 2.5rem;
}

/* Override PrimeVue Password Styling agar width 100% */
:deep(.p-password) {
    width: 100%;
    display: block;
}

:deep(.p-password-input) {
    width: 100%;
}
</style>