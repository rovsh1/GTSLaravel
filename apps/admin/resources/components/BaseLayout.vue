<script setup lang="ts">

import { MaybeRef } from '@vueuse/core'

import LoadingSpinner from '~components/LoadingSpinner.vue'

const props = withDefaults(defineProps<{
  title?: string
  loading?: MaybeRef<boolean>
}>(), {
  title: undefined,
  loading: false,
})

</script>
<template>
  <div class="baseLayout">
    <LoadingSpinner v-if="loading" class="loadingSpinner" />
    <template v-else>
      <div class="content-header">
        <div v-if="title !== undefined || $slots['title']" class="title">
          <slot v-if="$slots['title']" name="title" />
          <template v-else-if="title !== undefined">
            {{ title }}
          </template>
        </div>
        <slot name="header-controls" />
      </div>
      <div class="content-body">
        <slot />
      </div>
    </template>
  </div>
</template>
<style lang="scss" scoped>
.baseLayout {
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
  width: 100%;
  min-height: 10em;
}

.loadingSpinner {
  font-size: 2em;
}

.content-header {
  display: flex;
  gap: 1em;
  width: 100%;
}

.content-body {
  width: 100%;
}
</style>
