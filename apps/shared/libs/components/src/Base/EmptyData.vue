<script setup lang="ts">

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'

</script>

<template>
  <Card>
    <div class="empty-data mt-3">
      <slot />
    </div>
  </Card>
</template>

<style scoped lang="scss">
@use "~assets/sass/variables" as variables;

.empty-data {
  padding: 1rem;
  color: variables.$secondary;
  font-size: 0.875rem;
  text-align: center;
}
</style>
