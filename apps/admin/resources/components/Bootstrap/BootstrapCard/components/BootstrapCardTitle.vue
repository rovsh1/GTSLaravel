<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <h5 class="card-title">
    {{ title }}
  </h5>
</template>
