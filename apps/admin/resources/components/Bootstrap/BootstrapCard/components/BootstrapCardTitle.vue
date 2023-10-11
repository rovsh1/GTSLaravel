<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <div class="card-title">
    <h2>{{ title }}</h2>
  </div>
</template>
