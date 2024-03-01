<script lang="ts" setup>
import { ref, watch } from 'vue'

import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock'

const props = withDefaults(defineProps<{
  value: boolean
  is?: string
}>(), {
  is: 'div',
})

const root = ref<HTMLElement | null>(null)

watch(() => props.value, (value) => {
  if (root.value === null) return
  if (value) {
    disableBodyScroll(root.value)
  } else {
    enableBodyScroll(root.value)
  }
})
</script>
<template>
  <component :is="is" ref="root">
    <slot />
  </component>
</template>
