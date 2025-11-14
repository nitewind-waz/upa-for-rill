<template>
  <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
    <h3 class="text-gray-600 text-sm">{{ title }}</h3>
    <p class="text-2xl font-bold mt-2 text-slate-800">${{ value }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number],
})
</script>
