<script setup lang="ts">
import IconButton from '~components/IconButton.vue'

defineEmits<{
  (event: 'click'): void
}>()
</script>

<template>
  <IconButton icon="add" @click="$emit('click')" />
</template>
