<script setup lang="ts">

import LoadingSpinner from '~components/Base/LoadingSpinner.vue'
import { BootstrapSeverity } from '~components/Bootstrap/lib'

type SpinnerSeverity = BootstrapSeverity

type Severity = SpinnerSeverity
withDefaults(defineProps<{
  severity?: Severity
  show: boolean
}>(), {
  severity: 'light',
  show: false,
})

</script>

<template>
  <LoadingSpinner v-if="show" :severity="severity" class="button-spinner" />
</template>

<style scoped lang="scss">
.button-spinner {
  width: 11px;
  height: 12px;
  margin-left: 0.125rem;
}
</style>
