<script setup lang="ts">
import { type ComponentPublicInstance, type ImgHTMLAttributes, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import mediumZoom, { type Zoom, type ZoomOptions } from 'medium-zoom'

interface Props extends ImgHTMLAttributes {
  options?: ZoomOptions
  disabled?: MaybeRef<boolean>
}

const props = withDefaults(defineProps<Props>(), {
  options: undefined,
  disabled: false,
})

let zoom: Zoom | null = null

function getInstance() {
  if (zoom === null) {
    zoom = mediumZoom(props.options)
  }

  return zoom
}

function attachZoom(ref: Element | ComponentPublicInstance | null) {
  const image = ref as HTMLImageElement | null
  const instance = getInstance()

  if (image) {
    instance.attach(image)
  } else {
    instance.detach()
  }
}

watch(() => props.options, (options) => {
  getInstance().update(options || {})
})
</script>
<template>
  <img
    :ref="attachZoom"
    :alt="props.alt"
    :src="props.src"
    class="zoomableImage"
    :class="{ disabled }"
  />
</template>
<style lang="scss" scoped>
.zoomableImage {
  &.disabled {
    pointer-events: none;
  }
}
</style>
<style>
.medium-zoom-overlay {
  z-index: 10000;
}

.medium-zoom-image--opened {
  z-index: 10001;
}
</style>
