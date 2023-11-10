<script setup lang="ts">
defineProps<{
  title: string
}>()
</script>

<template>
  <div v-bind="$attrs" class="position-relative mt-3 rounded shadow-lg p-4">
    <div class="control-panel-section-title">
      <span>
        <h6>{{ title }}</h6>
      </span>
      <span>
        <slot name="actions" />
      </span>
    </div>

    <hr>
    <slot />
  </div>
</template>

<style scoped lang="scss">
hr {
  margin: 0.5rem 0 0.75rem;
}

.control-panel-section-title {
  display: flex;
  justify-content: space-between;
  align-items: center;

  h6 {
    margin-bottom: 0;
  }
}
</style>
