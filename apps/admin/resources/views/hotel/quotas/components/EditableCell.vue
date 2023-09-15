<script lang="ts" setup>
import { onMounted, onUnmounted, ref, watch, watchEffect } from 'vue'
import InlineSVG from 'vue-inline-svg'

import cmdIcon from '@mdi/svg/svg/apple-keyboard-command.svg'
import ctrlIcon from '@mdi/svg/svg/apple-keyboard-control.svg'
import shiftIcon from '@mdi/svg/svg/apple-keyboard-shift.svg'
import escapeIcon from '@mdi/svg/svg/keyboard-esc.svg'
import enterIcon from '@mdi/svg/svg/keyboard-return.svg'
import { onClickOutside } from '@vueuse/core'
import { Tooltip } from 'floating-vue'
import { GenericValidateFunction, useField } from 'vee-validate'
import { z } from 'zod'

import { usePlatformDetect } from '~lib/platform'

type CellKey = string | null

type RangeError = (min: number, max: number) => string

const defaultMin = 1

const defaultRangeError: RangeError = (min, max) => `Введите от ${min} до ${max}`

const props = withDefaults(defineProps<{
  cellKey: string
  activeKey: CellKey
  value: string
  initValue: string
  max: number
  rangeError?: RangeError
  disabled: boolean
  inRange: boolean
  dayMenuRef?: HTMLElement | null
  dayMenuActionCompleted?: boolean
}>(), {
  rangeError: (min, max) => `Введите от ${min} до ${max}`,
  dayMenuRef: null,
  dayMenuActionCompleted: false,
})

const emit = defineEmits<{
  (event: 'active-key', value: CellKey): void
  (event: 'range-key', value: CellKey): void
  (event: 'pick-key', value: CellKey): void
  (event: 'input', value: number): void
  (event: 'value', value: number): void
  (event: 'context-menu', value: HTMLElement): void
  (event: 'reset'): void
  (event: 'resetEditedValueToInit', value: number): void
}>()

const buttonRef = ref<HTMLButtonElement | null>(null)

const inputRef = ref<HTMLInputElement | null>(null)

const wrapperRef = ref<HTMLInputElement | null>(null)

const rangeMode = ref(false)
const pickMode = ref(false)

const { isMacOS } = usePlatformDetect()

const singleDayTooltip = 'Нажмите правой кнопкой мыши, чтобы открыть, закрыть или сбросить статус этого дня'

const handleInputMount = (input: HTMLInputElement) => {
  input.focus()
  setTimeout(() => {
    input.select()
  }, 0)
}

watch(inputRef, (element) => {
  if (element === null) return
  handleInputMount(element)
})

const parseValue = (value: string) => z.coerce.number().parse(value)

const validateValue: GenericValidateFunction<typeof props.value | undefined> = (value) => {
  if (value === undefined) return 'undefined'
  const { max, rangeError } = props
  const number = parseValue(value)
  if (number < 1) {
    return defaultRangeError(defaultMin, max)
  }
  if (number > max) {
    return rangeError(defaultMin, max)
  }
  return true
}

const { value: valueModel, errorMessage } = useField(() => props.value, validateValue)

watch(() => props.value, (value) => {
  if (valueModel.value !== value) {
    valueModel.value = value
  }
})

watch(valueModel, (value) => {
  if (value === undefined || value === props.value) return
  emit('input', parseValue(value))
})

const handleButtonClick = (event: MouseEvent) => {
  if (rangeMode.value) {
    event.preventDefault()
    emit('range-key', props.cellKey)
    return
  }
  if (pickMode.value) {
    event.preventDefault()
    emit('pick-key', props.cellKey)
    return
  }
  emit('active-key', props.cellKey)
}

const onPressEsc = () => {
  inputRef.value?.blur()
  emit('reset')
  emit('active-key', null)
  emit('range-key', null)
  emit('pick-key', null)
  pickMode.value = false
  rangeMode.value = false
}

const onPressEnter = () => {
  if (
    errorMessage.value !== undefined
    || valueModel.value === undefined
  ) return
  emit('value', parseValue(valueModel.value))
}

const handleKeyDown = (event: KeyboardEvent) => {
  if (props.disabled || props.activeKey === null) return
  if (event.shiftKey) rangeMode.value = true
  if (event.ctrlKey || event.metaKey) pickMode.value = true
}

const handleKeyUp = () => {
  rangeMode.value = false
  pickMode.value = false
}

const handleContextMenu = (event: MouseEvent) => {
  const isInputRightClick = event.target === inputRef.value
  const isButtonInRangeRightClick = event.target === buttonRef.value

  if ((isInputRightClick || isButtonInRangeRightClick) && !props.disabled) {
    event.preventDefault()
    emit('context-menu', event.target as HTMLElement)
  }
}

onClickOutside(inputRef, (event: MouseEvent) => {
  if (props.dayMenuRef && props.dayMenuRef instanceof Element && event.target) {
    if (props.dayMenuRef.contains(event.target as Node)) {
      return
    }
  }
  if (!rangeMode.value && !pickMode.value) {
    if (props.inRange || (valueModel.value !== props.initValue && valueModel.value !== '0')) {
      onPressEnter()
    } else if (!props.inRange && (valueModel.value === props.initValue || valueModel.value === '0')) {
      onPressEsc()
    }
  }
})

watch(() => props.disabled, (value) => {
  if (value === true) {
    onPressEsc()
  }
})

watch(() => props.dayMenuActionCompleted, (value) => {
  if (value === true) {
    onPressEsc()
  }
})

watchEffect(() => {
  if (props.activeKey === props.cellKey) {
    emit('resetEditedValueToInit', parseValue(props.initValue))
  }
})

onMounted(() => {
  window.addEventListener('keydown', handleKeyDown)
  window.addEventListener('keyup', handleKeyUp)
  window.addEventListener('contextmenu', handleContextMenu)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
  window.removeEventListener('keyup', handleKeyUp)
  window.removeEventListener('contextmenu', handleContextMenu)
})
</script>
<template>
  <Tooltip
    v-if="activeKey === cellKey && !disabled"
    ref="wrapperRef"
    :theme="errorMessage === undefined ? 'tooltip' : 'danger-tooltip'"
    handle-resize
  >
    <label>
      <input
        :ref="(element) => inputRef = element as HTMLInputElement"
        v-model="valueModel"
        class="editableCellInput"
        :class="{ isInvalid: errorMessage !== undefined }"
        type="number"
        @blur="(event) => {
          event.preventDefault()
          inputRef?.focus()
        }"
        @keydown.esc="onPressEsc"
        @keydown.enter="onPressEnter"
      />
    </label>
    <template #popper>
      <div v-if="errorMessage">{{ errorMessage }}</div>
      <template v-else>
        <div>{{ singleDayTooltip }}</div>
        <div>Нажмите <InlineSVG :src="enterIcon" /> Enter, чтобы подтвердить изменения</div>
        <div>
          Нажмите
          <template v-if="isMacOS">⎋ Esc</template>
          <template v-else><InlineSVG :src="escapeIcon" /> Esc</template>,
          чтобы отменить изменения и сбросить выделение
        </div>
      </template>
    </template>
  </Tooltip>
  <Tooltip v-else :delay="{ show: 2000 }">
    <button
      :ref="(element) => buttonRef = element as HTMLButtonElement"
      type="button"
      class="editableDataCell"
      :class="{ inRange }"
      :disabled="disabled"
      @mousedown.prevent
      @click="handleButtonClick"
    >
      <slot />
    </button>
    <template #popper>
      <template v-if="activeKey !== null">
        <div>Зажмите <InlineSVG :src="shiftIcon" /> Shift и кликните, чтобы задать значения для всех дней от выбранного до этого.</div>
        <div>
          Зажмите <template v-if="isMacOS">
            <InlineSVG :src="cmdIcon" /> command
          </template><template v-else>
            <InlineSVG :src="ctrlIcon" /> Ctrl
          </template> и кликните, чтобы добавить день в выборку или удалить из неё.
        </div>
      </template>
      <template v-if="inRange">
        <div>Нажмите правой кнопкой мыши, чтобы открыть, закрыть или сбросить статус выбранных дней</div>
      </template>
      <template v-else>
        <div>{{ singleDayTooltip }}</div>
      </template>
    </template>
  </Tooltip>
</template>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;
@use './shared' as shared;

%cell {
  @include shared.cell;
}

%data-cell {
  @include shared.data-cell;
}

.editableCellInput {
  @extend %data-cell;

  width: 100%;
  font-size: inherit;
  appearance: textfield;

  &::-webkit-outer-spin-button,
  &::-webkit-inner-spin-button {
    margin: 0;
    appearance: none;
  }

  &.isInvalid {
    outline-color: bs.$danger;
  }
}

.editableDataCell {
  @extend %data-cell;

  width: 100%;
  border: 2px solid transparent;
  border-radius: 2px;
  background: unset;

  &:not(:disabled) {
    &:hover {
      background-color: rgba(black, 0.1);
    }
  }

  &,
  &:disabled {
    color: inherit;
  }

  &.inRange {
    border-color: rgba(blue, 0.3);
  }
}

svg {
  width: auto;
  height: 1em;
  fill: currentcolor;
}
</style>
