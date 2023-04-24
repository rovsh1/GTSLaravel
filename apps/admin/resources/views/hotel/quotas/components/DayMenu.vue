<script lang="ts" setup>
import { computed } from 'vue'

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
      <button type="button" class="button" @click="openDay">
        Открыть
      </button>
    </li>
    <li>
      <button type="button" class="button" @click="closeDay">
        Закрыть
      </button>
    </li>
    <li>
      <button type="button" class="button" @click="resetDay">
        Сбросить
      </button>
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
  background-color: vars.$body-bg;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 20%);
  list-style: none;
}

.button {
  width: 100%;
  padding: 0.25em 0.5em;
  border: unset;
  background: unset;

  &:hover {
    background-color: rgba(black, 0.1);
  }
}
</style>
