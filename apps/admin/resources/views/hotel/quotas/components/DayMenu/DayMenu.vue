<script lang="ts" setup>
import { computed } from 'vue'

import BaseButton from '~resources/components/BaseButton.vue'

const props = defineProps<{
  menuRef: HTMLElement | null
  menuDayKey: string | null
}>()

const emit = defineEmits<{
  (event: 'close'): void
}>()

const menuTop = computed(() => {
  if (props.menuRef === null) return 0
  const { top, height } = props.menuRef.getBoundingClientRect()
  return top + height
})

const menuLeft = computed(() => {
  if (props.menuRef === null) return 0
  const { left } = props.menuRef.getBoundingClientRect()
  return left
})

const closeMenu = () => {
  emit('close')
}

const openDay = () => {
  console.log('open', props.menuDayKey)
  closeMenu()
}

const closeDay = () => {
  console.log('close', props.menuDayKey)
  closeMenu()
}

const resetDay = () => {
  console.log('reset', props.menuDayKey)
  closeMenu()
}
</script>
<template>
  <ul class="menu">
    <li>
      <BaseButton
        size="small"
        class="button"
        label="Открыть"
        @click="openDay"
      />
    </li>
    <li>
      <BaseButton
        size="small"
        class="button"
        label="Закрыть"
        @click="closeDay"
      />
    </li>
    <li>
      <BaseButton
        size="small"
        severity="danger"
        class="button"
        label="Сбросить"
        @click="resetDay"
      />
    </li>
  </ul>
</template>
<style lang="scss" scoped>
@use '~resources/sass/variables' as vars;

.menu {
  position: fixed;
  top: calc(v-bind(menuTop) * 1px);
  left: calc(v-bind(menuLeft) * 1px);
  margin: unset;
  padding: unset;
  border-radius: 0.375em;
  background-color: vars.$body-bg;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 20%);
  list-style: none;

  li {
    &:first-child {
      .button {
        border-bottom-right-radius: unset;
        border-bottom-left-radius: unset;
      }
    }

    &:last-child {
      .button {
        border-top-left-radius: unset;
        border-top-right-radius: unset;
      }
    }

    &:not(:first-child) {
      .button {
        border-top-left-radius: unset;
        border-top-right-radius: unset;
      }
    }

    &:not(:last-child) {
      .button {
        border-bottom-right-radius: unset;
        border-bottom-left-radius: unset;
      }
    }
  }

}

.button {
  width: 100%;
}
</style>
