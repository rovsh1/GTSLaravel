<script setup lang="ts">

defineProps<{
  title: string
  id: string
}>()

</script>

<template>
  <div class="card">
    <div class="card-header collapsable collapsed">
      <div
        class="card-header-toggle"
        data-bs-toggle="collapse"
        :data-bs-target="`#collapse-${id}`"
        aria-expanded="false"
        :aria-controls="`collapse-${id}`"
      />
      <h5 class="mb-0">
        {{ title }}
      </h5>
      <div class="spacer" />
      <div class="card-header-controls">
        <slot class="" name="header-controls" />
      </div>
    </div>

    <div :id="`collapse-${id}`" class="collapse">
      <div class="card-body">
        <slot />
      </div>
    </div>
  </div>
</template>
