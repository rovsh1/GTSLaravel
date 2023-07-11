<script setup lang="ts">

import { computed, ref, watch } from 'vue'
import InlineSVG from 'vue-inline-svg'

import escapeIcon from '@mdi/svg/svg/keyboard-esc.svg'
import enterIcon from '@mdi/svg/svg/keyboard-return.svg'
import { onClickOutside, useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'

import { usePlatformDetect } from '~lib/platform'

const props = withDefaults(defineProps<{
  value: number | undefined
  placeholder?: string
  dimension?: string
  emptyValue?: string
  hideClickOutside?: boolean
}>(), {
  placeholder: undefined,
  emptyValue: undefined,
  dimension: undefined,
  hideClickOutside: true,
})

const emit = defineEmits<{
  (event: 'change', value: number): void
}>()

const [isEditable, toggleEditable] = useToggle()
const { isMacOS } = usePlatformDetect()

const inputRef = ref<HTMLInputElement | null>(null)
const editedValue = ref<number>()
const isChanged = ref(false)

const localValue = computed<number>({
  get: () => (isChanged.value ? editedValue.value : props.value) as number,
  set: (value: number) => {
    editedValue.value = value
    isChanged.value = true
  },
})

const displayValue = computed(() => {
  if (!localValue.value) {
    return props.emptyValue || 'Не установлена'
  }
  const dimensionText = props.dimension && localValue ? props.dimension : ''

  return `${localValue.value} ${dimensionText}`
})

watch(inputRef, (element) => {
  if (element === null) {
    return
  }
  element.focus()
})

const hideEditable = () => {
  inputRef.value?.blur()
  isChanged.value = false
  toggleEditable(false)
}

const onPressEnter = () => {
  emit('change', localValue.value)
  toggleEditable(false)
}

const onPressEsc = () => {
  hideEditable()
}

if (props.hideClickOutside) {
  onClickOutside(inputRef, hideEditable)
}

</script>

<template>
  <div>
    <a v-if="!isEditable" href="#" @click.prevent="toggleEditable(true)">
      {{ displayValue }}
    </a>

    <Tooltip
      v-else
      theme="tooltip"
      handle-resize
    >
      <input
        ref="inputRef"
        v-model.number="localValue"
        class="form-control"
        :placeholder="placeholder"
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
