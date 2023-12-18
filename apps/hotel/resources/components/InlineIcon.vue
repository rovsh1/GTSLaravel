<script lang="ts" setup>
defineProps<{
  icon: string
}>()
</script>
<template>
  <i v-bind="$attrs" class="icon" aria-hidden="true">{{ icon }}</i>
</template>
<style lang="scss" scoped>
i {
  vertical-align: middle;
  line-height: 1;
  text-align: center;
}
</style>
