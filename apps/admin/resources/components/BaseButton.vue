<script lang="ts" setup>
import { computed } from 'vue'
import InlineSVG from 'vue-inline-svg'

type ButtonVariant = 'text' | 'filled' | 'outlined'

type ButtonSeverity = 'default' | 'primary' | 'danger'

type ButtonSize = 'default' | 'small'

const props = withDefaults(defineProps<{
  label: string
  onlyIcon?: string
  startIcon?: string
  endIcon?: string
  variant?: ButtonVariant
  severity?: ButtonSeverity
  size?: ButtonSize
  href?: string
  disabled?: boolean
}>(), {
  onlyIcon: undefined,
  startIcon: undefined,
  endIcon: undefined,
  variant: 'text',
  severity: 'default',
  size: 'default',
  href: undefined,
  disabled: false,
})

const emit = defineEmits<{
  (event: 'click'): void
}>()

const variantClassByVariant: Record<ButtonVariant, string> = {
  text: 'variantText',
  filled: 'variantFilled',
  outlined: 'variantOutlined',
}

const severityClassBySeverity: Record<ButtonSeverity, string> = {
  default: 'severityDefault',
  primary: 'severityPrimary',
  danger: 'severityDanger',
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
    :class="[
      variantClassByVariant[variant],
      severityClassBySeverity[severity],
      sizeClassBySize[size],
      { onlyIcon },
    ]"
    :href="disabled ? undefined : href"
    :title="onlyIcon ? label : undefined"
    :disabled="isDisabled"
    @click="emit('click')"
  >
    <slot name="start" />
    <template v-if="startIcon">
      <InlineSVG :src="startIcon" class="buttonIcon" />
    </template>
    <template v-if="onlyIcon">
      <InlineSVG :src="onlyIcon" class="buttonIcon" />
    </template>
    <template v-else>
      {{ label }}
    </template>
    <template v-if="endIcon">
      <InlineSVG :src="endIcon" class="buttonIcon" />
    </template>
    <slot name="end" />
  </component>
</template>
<style lang="scss" scoped>
@use 'sass:color' as color;
@use '~resources/sass/variables' as vars;

@mixin active-state($color, $hover-bg, $active-bg) {
  &:not(:disabled) {
    &:hover {
      background-color: $hover-bg;
    }

    &:active {
      border-color: $color;
      background-color: $active-bg;
    }
  }
}

@mixin text-severity($color) {
  @include active-state($color, rgba($color, 0.1), rgba($color, 0.2));

  color: $color;
}

@mixin filled-severity($color, $bg) {
  @include active-state($bg, rgba($bg, 0.8), rgba($bg, 0.6));

  background-color: $bg;
  color: $color;
}

@mixin outlined-severity($color) {
  @include active-state($color, rgba($color, 0.1), rgba($color, 0.2));

  border-color: $color;
  color: $color;
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

  &:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  &.variantText {
    &.severityDefault {
      @include text-severity(black);
    }

    &.severityPrimary {
      @include text-severity(vars.$primary);
    }

    &.severityDanger {
      @include text-severity(vars.$error);
    }
  }

  &.variantFilled {
    &.severityDefault {
      @include filled-severity(white, black);
    }

    &.severityPrimary {
      @include filled-severity(white, vars.$primary);
    }

    &.severityDanger {
      @include filled-severity(white, vars.$error);
    }
  }

  &.variantOutlined {
    &.severityDefault {
      @include outlined-severity(black);
    }

    &.severityPrimary {
      @include outlined-severity(vars.$primary);
    }

    &.severityDanger {
      @include outlined-severity(vars.$error);
    }
  }

  &.sizeDefault {
    padding: 0.5em 1em;
  }

  &.sizeSmall {
    padding: 0.3em 0.8em;
    font-size: 0.9em;
  }

  &.onlyIcon {
    padding: 0.5em;
    border-radius: 20em;
  }
}

.buttonIcon {
  width: var(--button-icon-size);
  fill: currentcolor;
  aspect-ratio: 1/1;
}
</style>
