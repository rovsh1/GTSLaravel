<script setup lang="ts">
import { onMounted } from 'vue'

import { DetailsFormData } from './lib/types'

const emit = defineEmits<{
  (event: 'formCompleted', value: DetailsFormData | undefined): void
}>()

onMounted(() => {
  emit('formCompleted', {})
})
</script>

<template>
  <div style="display: none;" />
</template>
