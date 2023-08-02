<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <div v-bind="$attrs">
    <h6>{{ title }}</h6>
    <hr>
    <slot />
  </div>
</template>

<style scoped lang="scss">
hr {
  margin: 0.5rem 0 0.75rem;
}
</style>
