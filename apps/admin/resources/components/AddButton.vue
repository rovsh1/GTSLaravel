<script setup lang="ts">
defineProps({
  text: {
    type: String,
    required: true,
  },
})

defineEmits(['click'])
</script>

<template>
  <button type="button" class="btn btn-add" @click="$emit('click')">
    <i class="icon">add</i>
    {{ text }}
  </button>
</template>
