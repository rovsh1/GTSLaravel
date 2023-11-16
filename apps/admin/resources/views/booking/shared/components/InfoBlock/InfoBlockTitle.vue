<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <h6 v-bind="$attrs" class="mb-0">{{ title }}</h6>
</template>
