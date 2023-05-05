<script lang="ts" setup>
import { computed, ref } from 'vue'

import { flip, useFloating } from '@floating-ui/vue'

import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'

const props = defineProps<{
  menuRef: HTMLElement | null
  menuDayKey: string | null
}>()

const emit = defineEmits<{
  (event: 'close'): void
}>()

const reference = computed(() => props.menuRef)

const floating = ref(null)

const { floatingStyles } = useFloating(reference, floating, {
  middleware: [flip()],
  placement: 'bottom-start',
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
  <div ref="floating" :style="floatingStyles">
    <div class="btn-group-vertical list-group menu">
      <BootstrapButton
        size="small"
        severity="light"
        label="Открыть"
        @click="openDay"
      />
      <BootstrapButton
        size="small"
        severity="light"
        label="Закрыть"
        @click="closeDay"
      />
      <BootstrapButton
        size="small"
        severity="danger"
        label="Сбросить"
        @click="resetDay"
      />
    </div>
  </div>
</template>
<style scoped>
.menu {
  border: solid var(--bs-list-group-border-width) var(--bs-list-group-border-color);
  border-radius: var(--bs-list-group-border-radius);
}
</style>
