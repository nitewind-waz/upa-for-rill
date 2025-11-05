<template>
  <div class="p-6 flex flex-col items-center gap-4">
    <h1 class="text-2xl font-bold mb-2">Hello PrimeVue + Inertia!</h1>

    <Button label="Click Me" icon="pi pi-check" @click="clicked" />
    <Button label="Toggle Dark Mode" @click="toggleDarkMode()" />
    <FloatLabel>
        <InputText id="over_label" v-model="value1" />
        <label for="over_label">Over Label</label>
    </FloatLabel>

    <FloatLabel variant="in">
        <InputText id="in_label" v-model="value2" variant="filled" />
        <label for="in_label">In Label</label>
    </FloatLabel>

    <FloatLabel variant="on">
        <InputText id="on_label" v-model="value3" />
        <label for="on_label">On Label</label>
    </FloatLabel>
    
    <Toast />
  </div>
</template>

<script setup>
import Button from 'primevue/button'
import Toast from 'primevue/toast'
import InputText from 'primevue/inputtext';
import FloatLabel from 'primevue/floatlabel';
import { useToast } from 'primevue/usetoast'

function toggleDarkMode() {
    document.documentElement.classList.toggle('my-app-dark');
}

const toast = useToast()
const clicked = () => {
  toast.add({
    severity: 'success',
    summary: 'Success',
    detail: '!',
    life: 3000
  })
}
</script>

<style scoped>
/* Tambahkan custom style di sini kalau perlu */
</style>