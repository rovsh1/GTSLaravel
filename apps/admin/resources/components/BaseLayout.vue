<script setup lang="ts">

defineProps({
  title: {
    type: String,
    default: null,
  },
})

</script>

<template>
  <div class="content-header">
    <div class="title" v-if="title">
      {{ title }}
    </div>
    <slot name="header-controls" />
  </div>

  <div class="content-body">
    <slot name="body" />
  </div>
</template>
