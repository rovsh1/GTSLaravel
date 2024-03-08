<script lang="ts" setup>
import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import InlineIcon from '~components/Base/InlineIcon.vue'
import LoadingSpinner from '~components/Base/LoadingSpinner.vue'
import { BootstrapSeverity } from '~components/Bootstrap/lib'

import { ButtonSeverity, ButtonSize, ButtonType, ButtonVariant } from './lib'

const props = withDefaults(defineProps<{
  type?: ButtonType
  label: string
  onlyIcon?: string
  startIcon?: string
  endIcon?: string
  variant?: ButtonVariant
  severity?: ButtonSeverity
  size?: ButtonSize
  href?: string
  disabled?: MaybeRef<boolean>
  loading?: MaybeRef<boolean>
}>(), {
  type: 'auto',
  onlyIcon: undefined,
  startIcon: undefined,
  endIcon: undefined,
  variant: 'filled',
  severity: 'primary',
  size: 'default',
  href: undefined,
  disabled: false,
  loading: false,
})

const emit = defineEmits<{
  (event: 'click'): void
}>()

const filledSeverityClassBySeverity: Record<ButtonSeverity, string> = {
  primary: 'btn-primary',
  secondary: 'btn-secondary',
  success: 'btn-success',
  danger: 'btn-danger',
  warning: 'btn-warning',
  info: 'btn-info',
  light: 'btn-light',
  dark: 'btn-dark',
  link: 'btn-link',
}

const outlineSeverityClassBySeverity: Record<ButtonSeverity, string> = {
  primary: 'btn-outline-primary',
  secondary: 'btn-outline-secondary',
  success: 'btn-outline-success',
  danger: 'btn-outline-danger',
  warning: 'btn-outline-warning',
  info: 'btn-outline-info',
  light: 'btn-outline-light',
  dark: 'btn-outline-dark',
  link: 'btn-outline-link',
}

const sizeClassBySize: Record<ButtonSize, string> = {
  large: 'btn-lg',
  default: '',
  small: 'btn-sm',
}

const severityClass = computed(() => {
  switch (props.variant) {
    case 'filled':
      return filledSeverityClassBySeverity[props.severity]
    case 'outline':
      return outlineSeverityClassBySeverity[props.severity]
    default:
      return ''
  }
})

const isDisabled = computed<boolean | undefined>(() => {
  if (unref(props.loading)) return true
  if (props.href !== undefined && props.disabled) return true
  if (props.href === undefined) return unref(props.disabled)
  return undefined
})

const is = computed<'a' | 'button'>(() => {
  if (props.disabled || isDisabled.value) return 'button'
  if (props.href === undefined) return 'button'
  return 'a'
})

const buttonType = computed(() => {
  if (props.href === undefined) {
    if (props.type === 'auto') return 'button'
    return props.type
  }
  return undefined
})
</script>
<template>
  <component
    :is="is"
    v-tooltip="onlyIcon ? label : undefined"
    :type="buttonType"
    class="btn button"
    :class="[
      severityClass,
      sizeClassBySize[size],
      { onlyIcon, isLoading: loading },
    ]"
    :href="disabled ? undefined : href"
    :title="onlyIcon ? label : undefined"
    :disabled="isDisabled"
    @click="emit('click')"
  >
    <div v-if="loading" class="loadingOverlay">
      <LoadingSpinner :severity="severity as BootstrapSeverity" class="spinner" />
    </div>
    <slot name="start" />
    <template v-if="startIcon">
      <InlineIcon :icon="startIcon" class="buttonIcon" />
    </template>
    <template v-if="onlyIcon">
      <InlineIcon :icon="onlyIcon" class="buttonOnlyIcon" />
    </template>
    <span v-else-if="label" class="buttonLabel">
      {{ label }}
    </span>
    <template v-if="endIcon">
      <InlineIcon :icon="endIcon" class="buttonIcon" />
    </template>
    <slot name="end" />
  </component>
</template>
<style lang="scss" scoped>
// temporary fixes: should move to global styles

.btn-primary {
  &,
  &:hover,
  &:active {
    --bs-btn-color: white;
    --bs-btn-hover-color: var(--bs-btn-color);
    --bs-btn-active-color: var(--bs-btn-color);
  }
}

.btn-outline-primary {
  &:hover,
  &:active {
    --bs-btn-color: white;
    --bs-btn-hover-color: var(--bs-btn-color);
    --bs-btn-active-color: var(--bs-btn-color);
  }
}

.btn-outline-light {
  --bs-btn-color: hsl(0deg, 0%, 40%);
  --bs-btn-border-color: hsl(0deg, 0%, 80%);

  &:disabled {
    --bs-btn-disabled-color: var(--bs-btn-color);
  }
}

.btn-outline-link {
  border-color: transparent;
}

button.btn {
  height: auto;
}

a.btn {
  line-height: unset;
}

.btn-sm {
  --bs-btn-line-height: unset;
}

//

.btn {
  &.btn-outline-link {
    color: inherit;
  }
}

.button {
  --button-icon-size: 1.5em;

  position: relative;
  display: inline-flex;
  gap: 0.5em;
  align-items: center;
  transition-duration: 0.3s;

  &.onlyIcon {
    padding: 0.5em;
    border-radius: 20em;
    //color: inherit;

    &.sizeTiny {
      padding: 0;
    }
  }

  &.isLoading {
    opacity: 1;

    %loading-button-label {
      opacity: 0.5;
    }

    %loading-button-icon {
      opacity: 0;
    }
  }
}

.loadingOverlay {
  position: absolute;
  inset: -1px;
  z-index: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: inherit;
  background-color: rgba(white, 0.5);
}

.spinner {
  --bs-spinner-width: 1.5em;
  --bs-spinner-height: var(--bs-spinner-width);
  --bs-spinner-border-width: 0.15em;
}

.buttonIcon {
  @extend %loading-button-icon;

  font-size: 1.25rem;
}

.buttonOnlyIcon {
  @extend %loading-button-icon;

  font-size: 1.25rem;
}

.buttonLabel {
  @extend %loading-button-label;
}
</style>
