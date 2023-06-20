<script setup lang="ts">

import { computed, ref } from 'vue'
import InlineSVG from 'vue-inline-svg'

import escapeIcon from '@mdi/svg/svg/keyboard-esc.svg'
import enterIcon from '@mdi/svg/svg/keyboard-return.svg'
import { useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'

import { usePlatformDetect } from '~lib/platform'

const props = defineProps<{
  value: number | undefined
  placeholder?: string
}>()

const emit = defineEmits<{
  (event: 'change', value: number): void
}>()

const [isEditable, toggleEditable] = useToggle()
const { isMacOS } = usePlatformDetect()

const editedValue = ref<number>()
const isChanged = ref(false)

const localValue = computed<number>({
  get: () => (isChanged.value ? editedValue.value : props.value) as number,
  set: (value: number) => {
    editedValue.value = value
    isChanged.value = true
  },
})

const onPressEnter = () => {
  emit('change', localValue.value)
  toggleEditable(false)
}

const onPressEsc = () => {
  isChanged.value = false
  toggleEditable(false)
}

</script>

<template>
  <div>
    <a v-if="!isEditable" href="#" @click.prevent="toggleEditable(true)">{{ localValue || 'Не установлена' }}</a>

    <Tooltip
      v-else
      theme="tooltip"
      handle-resize
    >
      <input
        v-model="localValue"
        class="form-control"
        type="number"
        @keydown.esc="onPressEsc"
        @keydown.enter="onPressEnter"
      />
      <template #popper>
        <div>Нажмите <InlineSVG :src="enterIcon" /> Enter, чтобы подтвердить изменения</div>
        <div>
          Нажмите
          <template v-if="isMacOS">⎋ Esc</template>
          <template v-else><InlineSVG :src="escapeIcon" /> Esc</template>,
          чтобы отменить изменения и сбросить выделение
        </div>
      </template>
    </Tooltip>
  </div>
</template>

<style lang="scss" scoped>
svg {
  width: auto;
  height: 1em;
  fill: currentcolor;
}
</style>
