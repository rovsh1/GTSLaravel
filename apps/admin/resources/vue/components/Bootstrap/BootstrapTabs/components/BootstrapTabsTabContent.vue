<script setup lang="ts">

defineProps<{
  tabname?: string
  isActive: Boolean
}>()
</script>

<template>
  <div :id="tabname" role="tabpanel" :class="{ 'active show': isActive }" class="tab-pane fade row g-3">
    <slot />
  </div>
</template>

<style scoped lang="scss">
div {
  display: none;
  height: 0;
  visibility: hidden;
}

div.active {
  display: block;
  height: auto;
  visibility: visible;
}
</style>
