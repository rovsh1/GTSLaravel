<script lang="ts" setup>
import LoadingSpinner from '~components/Base/LoadingSpinner.vue'
</script>
<template>
  <div class="overlayLoading">
    <div class="overlayLoadingInner">
      <loading-spinner />
    </div>
  </div>
</template>
<style lang="scss" scoped>
.overlayLoading {
  position: absolute;
  inset: 0;
  z-index: 1;
  background-color: rgba(white, 0.7);
}

.overlayLoadingInner {
  position: sticky;
  top: 0;
  z-index: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  max-height: 100vh;
}
</style>
