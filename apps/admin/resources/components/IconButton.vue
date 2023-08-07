<script setup lang="ts">
defineProps<{
  icon: string
}>()

defineEmits<{
  (event: 'click'):void
}>()
</script>

<template>
  <a
    href="#"
    @click.prevent="$emit('click')"
  >
    <i class="icon">add</i>
    <slot />
  </a>
</template>

<style scoped lang="scss">
a i {
  vertical-align: top;
  margin-left: 5px;
  font-weight: bold;
  font-size: 18px;
}
</style>
