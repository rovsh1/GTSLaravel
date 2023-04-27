<script setup lang="ts">
import LoadingSpinner from '~resources/components/LoadingSpinner.vue'

withDefaults(defineProps<{
  title: string
  loading: boolean
}>(), {
  loading: false,
})
</script>
<template>
  <div class="baseLayout">
    <LoadingSpinner v-if="loading" class="loadingSpinner" />
    <template v-else>
      <div class="content-header">
        <div v-if="title" class="title">
          {{ title }}
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
  gap: 0.5em;
  width: 100%;
}

.content-body {
  width: 100%;
}
</style>
