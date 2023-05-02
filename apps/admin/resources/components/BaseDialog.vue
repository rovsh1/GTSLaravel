<script lang="ts" setup>
import closeIcon from '@mdi/svg/svg/close.svg'
import { OnClickOutside } from '@vueuse/components'
import { MaybeRef } from '@vueuse/core'

import BodyScrollLock from '~resources/components/BodyScrollLock.vue'
import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'

const emit = defineEmits<{
  (event: 'close'): void
}>()

const props = withDefaults(defineProps<{
  opened: boolean
  disabled?: MaybeRef<boolean>
}>(), {
  disabled: false,
})

const close = () => {
  if (props.disabled) return
  emit('close')
}
</script>
<template>
  <Teleport to="body">
    <BodyScrollLock :value="opened" class="dialog" :class="{ opened }">
      <div class="inner">
        <OnClickOutside class="body" @trigger="close">
          <template v-if="$slots['title']">
            <div class="title">
              <div class="titleLabel">
                <slot name="title" />
              </div>
              <BootstrapButton
                label="Закрыть"
                :only-icon="closeIcon"
                size="small"
                severity="link"
                variant="outline"
                @click="close"
              />
            </div>
          </template>
          <template v-if="$slots['default']">
            <div class="content">
              <slot />
            </div>
          </template>
          <template v-if="$slots['actions']">
            <div class="actions">
              <slot name="actions" />
            </div>
          </template>
        </OnClickOutside>
      </div>
    </BodyScrollLock>
  </Teleport>
</template>
<style lang="scss" scoped>
@use '~resources/sass/variables' as vars;

.dialog {
  --dialog-width: 40em;

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

.inner {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100%;
}

.body {
  @extend %opened-body;

  width: var(--dialog-width);
  margin: 1em;
  border: 1px solid rgba(black, 17.5%);
  border-radius: 0.5em;
  background-color: vars.$body-bg;
  box-shadow:
    0 3px 5px -1px rgba(black, 20%),
    0 6px 10px 0 rgba(black, 14%),
    0 1px 18px 0 rgba(black, 12%);
  transition: transform 0.3s ease;
  transform: translateY(-4em);
}

%part {
  padding: 1em;
}

.title {
  @extend %part;

  position: sticky;
  top: 0;
  display: flex;
  gap: 1em;
  align-items: start;
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

.actions {
  @extend %part;

  position: sticky;
  bottom: 0;
  display: flex;
  gap: 0.6em;
  justify-content: end;
  border-top: 1px solid hsl(210deg, 14%, 89%);
  border-bottom-right-radius: inherit;
  border-bottom-left-radius: inherit;
  background-color: inherit;
}
</style>
