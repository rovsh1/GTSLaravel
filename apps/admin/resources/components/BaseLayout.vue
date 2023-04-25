<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>
<template>
  <div class="content-header">
    <div v-if="title" class="title">
      {{ title }}
    </div>
    <slot name="header-controls" />
  </div>
  <div class="content-body">
    <slot />
  </div>
</template>
<style lang="scss" scoped>
.content-header {
  display: flex;
  gap: 0.5em;
}
</style>
