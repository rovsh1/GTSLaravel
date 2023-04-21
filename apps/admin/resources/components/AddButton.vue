<script setup lang="ts">
defineProps({
  text: {
    type: String,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['click'])
</script>

<template>
  <button
    type="button"
    :disabled="disabled"
    class="btn btn-add"
    @click="$emit('click')"
  >
    <i class="icon">add</i>
    {{ text }}
  </button>
</template>
