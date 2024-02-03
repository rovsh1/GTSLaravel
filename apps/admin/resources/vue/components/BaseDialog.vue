<script lang="ts" setup>
import { computed, watch } from 'vue'

import { OnClickOutside } from '@vueuse/components'
import { MaybeElementRef, MaybeRef, OnClickOutsideOptions } from '@vueuse/core'

import BodyScrollLock from '~components/BodyScrollLock.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'

const emit = defineEmits<{
  (event: 'close'): void
}>()

const props = withDefaults(defineProps<{
  opened: boolean
  autoWidth?: boolean
  disabled?: MaybeRef<boolean>
  loading?: MaybeRef<boolean>
  clickOutsideIgnore?: (MaybeElementRef | string)[]
  watchOtsideClick?: boolean
  keydownEnterCallback?: () => void
}>(), {
  disabled: false,
  loading: false,
  clickOutsideIgnore: undefined,
  autoWidth: false,
  watchOtsideClick: false,
  keydownEnterCallback: () => {},
})

const close = (isOutsideClick?: boolean) => {
  if (props.disabled || isOutsideClick) return
  emit('close')
}

const keydown = (event: KeyboardEvent) => {
  if (event.key === 'Escape') close()
  if (event.key === 'Enter') props.keydownEnterCallback()
}

const clickOutsideOptions = computed<OnClickOutsideOptions>(() => ({ ignore: props.clickOutsideIgnore }))

watch(() => props.opened, (value) => {
  if (value) {
    window.addEventListener('keydown', keydown)
  } else {
    window.removeEventListener('keydown', keydown)
  }
})
</script>
<template>
  <Teleport to="body">
    <BodyScrollLock :value="opened" class="dialog" :class="{ opened, 'auto-width': autoWidth }" v-bind="$attrs">
      <div class="inner">
        <OnClickOutside class="body" :options="clickOutsideOptions" @trigger="watchOtsideClick ? close() : close(true)">
          <template v-if="$slots['title']">
            <div class="title">
              <div class="titleLabel">
                <slot name="title" />
              </div>
              <BootstrapButton
                label="Закрыть (Esc)"
                only-icon="close"
                size="small"
                severity="link"
                variant="outline"
                :disabled="disabled"
                @click="close"
              />
            </div>
          </template>
          <template v-if="$slots['default']">
            <div class="content" :class="{ loading: loading }">
              <slot />
            </div>
          </template>
          <template v-if="$slots['actions-start'] || $slots['actions-end']">
            <div class="actions">
              <div class="actions-start">
                <slot name="actions-start" />
              </div>
              <div class="actions-end">
                <slot name="actions-end" />
              </div>
            </div>
          </template>
        </OnClickOutside>
      </div>
    </BodyScrollLock>
  </Teleport>
</template>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.dialog {
  --dialog-width: 40em;
  --part-padding: #{bs.$modal-inner-padding};
  --body-border: 1px solid #{rgba(black, 17.5%)};
  --body-margin: 1em;

  position: fixed;
  inset: 0;
  z-index: 1055;
  overflow: auto;
  background-color: rgba(black, 0.5);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;

  &.opened {
    opacity: 1;
    pointer-events: auto;

    %opened-body {
      transform: translateY(0);
    }
  }
}

.auto-width {
  --dialog-width: auto;
}

.inner {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100%;
}

.body {
  @extend %opened-body;

  width: var(--dialog-width);
  margin: var(--body-margin);
  border: var(--body-border);
  border-radius: bs.$modal-content-border-radius;
  background-color: bs.$body-bg;
  box-shadow:
    0 3px 5px -1px rgba(black, 20%),
    0 6px 10px 0 rgba(black, 14%),
    0 1px 18px 0 rgba(black, 12%);
  transition: transform 0.3s ease;
  transform: translateY(-4em);
}

%part {
  padding: var(--part-padding);
}

.title {
  @extend %part;

  position: sticky;
  top: 0;
  z-index: 1;
  display: flex;
  gap: 1em;
  align-items: flex-start;
  border-bottom: 1px solid hsl(210deg, 14%, 89%);
  border-top-left-radius: inherit;
  border-top-right-radius: inherit;
  background-color: inherit;
}

.titleLabel {
  flex-grow: 1;
  padding-block: 0.1em;
  font-weight: 500;
  font-size: 1.35em;
}

.content {
  @extend %part;
}

%actions-part {
  display: flex;
  gap: 0.6em;
}

.actions {
  @extend %part;
  @extend %actions-part;

  position: sticky;
  bottom: 0;
  align-items: flex-end;
  border-top: 1px solid hsl(210deg, 14%, 89%);
  border-bottom-right-radius: inherit;
  border-bottom-left-radius: inherit;
  background-color: inherit;
}

.actions-start {
  @extend %actions-part;

  flex-grow: 1;
}

.actions-end {
  @extend %actions-part;

  flex-shrink: 0;
}
</style>
