<script setup lang="ts">
defineProps({
  text: {
    type: String,
    default: 'Удалить',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['click'])
</script>

<template>
  <button type="button" class="btn btn-delete" :disabled="disabled" @click="$emit('click')">
    <i class="icon">delete</i>
    {{ text }}
  </button>
</template>
