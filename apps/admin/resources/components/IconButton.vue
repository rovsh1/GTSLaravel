<script setup lang="ts">
defineProps<{
  icon: string
}>()

defineEmits<{
  (event: 'click'): void
}>()
</script>

<template>
  <a href="#" @click.prevent="$emit('click')">
    <i class="icon">add</i>
    <slot />
  </a>
</template>

<style scoped lang="scss">
a {
  line-height: 1;

  i {
    vertical-align: top;
    margin-left: 0.313rem;
    font-weight: bold;
    font-size: 1.125rem;
  }
}
</style>
