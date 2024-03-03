<script setup lang="ts">
import IconButton from 'gts-components/Base/IconButton'

defineEmits<{
  (event: 'click'): void
}>()
</script>

<template>
  <IconButton icon="add" @click="$emit('click')" />
</template>
