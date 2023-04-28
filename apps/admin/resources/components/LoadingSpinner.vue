<script lang="ts" setup>
import { BootstrapSeverity } from '~resources/components/Bootstrap/lib'

type SpinnerSeverity = BootstrapSeverity

type Severity = SpinnerSeverity
defineProps<{
  severity?: Severity
}>()

const classBySeverity: Record<SpinnerSeverity, string> = {
  primary: 'text-primary',
  secondary: 'text-secondary',
  success: 'text-success',
  danger: 'text-danger',
  warning: 'text-warning',
  info: 'text-info',
  light: 'text-light',
  dark: 'text-dark',
}
</script>
<template>
  <div
    v-if="severity"
    class="spinner-border"
    :class="classBySeverity[severity]"
    role="status"
  >
    <span class="visually-hidden">Loading...</span>
  </div>
  <div v-else class="loadingSpinner" />
</template>
<style lang="scss" scoped>
.loadingSpinner {
  width: 2em;
  background-image: url("~resources/images/preload.svg");
  background-size: contain;
  aspect-ratio: 1/1;
}
</style>
