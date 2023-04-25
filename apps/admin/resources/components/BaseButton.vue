<script lang="ts" setup>
import { computed } from 'vue'
import InlineSVG from 'vue-inline-svg'

type ButtonVariant = 'default' | 'danger'

type ButtonSize = 'default' | 'small'

const props = withDefaults(defineProps<{
  label: string
  startIcon?: string
  endIcon?: string
  variant?: ButtonVariant
  size?: ButtonSize
  href?: string
  disabled?: boolean
}>(), {
  startIcon: undefined,
  endIcon: undefined,
  variant: 'default',
  size: 'default',
  href: undefined,
  disabled: false,
})

const emit = defineEmits<{
  (event: 'click'): void
}>()

const variantClassByVariant: Record<ButtonVariant, string> = {
  default: 'variantDefault',
  danger: 'variantDanger',
}

const sizeClassBySize: Record<ButtonSize, string> = {
  default: 'sizeDefault',
  small: 'sizeSmall',
}

const is = computed<'a' | 'button'>(() => {
  if (props.disabled) return 'button'
  if (props.href === undefined) return 'button'
  return 'a'
})

const isDisabled = computed<boolean | undefined>(() => {
  if (props.href !== undefined && props.disabled) return true
  if (props.href === undefined) return props.disabled
  return undefined
})
</script>
<template>
  <component
    :is="is"
    :type="href === undefined ? 'button' : undefined"
    class="button"
    :class="[variantClassByVariant[variant], sizeClassBySize[size]]"
    :href="disabled ? undefined : href"
    :disabled="isDisabled"
    @click="emit('click')"
  >
    <slot name="start" />
    <template v-if="startIcon">
      <InlineSVG :src="startIcon" class="buttonIcon" />
    </template>
    {{ label }}
    <template v-if="endIcon">
      <InlineSVG :src="endIcon" class="buttonIcon" />
    </template>
    <slot name="end" />
  </component>
</template>
<style lang="scss" scoped>
@use 'sass:color' as color;
@use '~resources/sass/variables' as vars;

@mixin variant($color) {
  color: $color;

  &:not(:disabled) {
    &:active {
      border-color: $color;
      background-color: rgba($color, 0.1);
    }
  }
}

.button {
  --button-icon-size: 1.5em;

  display: inline-flex;
  gap: 0.5em;
  padding: 0.5em 1em;
  border: 1px solid transparent;
  border-radius: 0.375em;
  background: unset;
  transition-duration: 0.3s;
  transition-property: background-color, color, border-color;

  &:not(:disabled) {
    &:hover {
      background-color: rgba(black, 0.08);
    }
  }

  &:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  &.variantDefault {
    @include variant(vars.$primary);
  }

  &.variantDanger {
    @include variant(vars.$error);
  }

  //&.sizeDefault {}

  &.sizeSmall {
    font-size: 0.9em;
  }
}

.buttonIcon {
  width: var(--button-icon-size);
  fill: currentcolor;
  aspect-ratio: 1/1;
}
</style>
