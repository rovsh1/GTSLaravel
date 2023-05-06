<script lang="ts" setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import InlineSVG from 'vue-inline-svg'

import cmdIcon from '@mdi/svg/svg/apple-keyboard-command.svg'
import ctrlIcon from '@mdi/svg/svg/apple-keyboard-control.svg'
import shiftIcon from '@mdi/svg/svg/apple-keyboard-shift.svg'
import escapeIcon from '@mdi/svg/svg/keyboard-esc.svg'
import enterIcon from '@mdi/svg/svg/keyboard-return.svg'
import { Tooltip } from 'floating-vue'

import { isMacOS } from '~lib/platform'

type CellKey = string | null

const props = defineProps<{
  cellKey: string
  activeKey: CellKey
  value: string
  max: number
  disabled: boolean
  inRange: boolean
}>()

const emit = defineEmits<{
  (event: 'active-key', value: CellKey): void
  (event: 'range-key', value: CellKey): void
  (event: 'pick-key', value: CellKey): void
  (event: 'input', value: number): void
  (event: 'value', value: number): void
  (event: 'context-menu', value: HTMLElement): void
  (event: 'reset'): void
}>()

const buttonRef = ref<HTMLButtonElement | null>(null)

const inputRef = ref<HTMLInputElement | null>(null)

const rangeMode = ref(false)
const pickMode = ref(false)

const macOS = computed(() => isMacOS())

const singleDayTooltip = 'Нажмите правой кнопкой мыши, чтобы открыть, закрыть или сбросить статус этого дня'

const handleInputMount = (input: HTMLInputElement) => {
  input.focus()
  input.select()
}

watch(inputRef, (element) => {
  if (element === null) return
  handleInputMount(element)
})

const validateInput = (value: string): number | null => {
  if (value === '') return null
  const number = Number(value)
  if (isNaN(number)) return null
  return number
}

const handleValue = (target: EventTarget | null, done: (value: number) => void) => {
  if (target === null) return
  const { value } = target as HTMLInputElement
  const number = validateInput(value)
  if (number === null) return
  done(number)
}

const handleChange = (target: EventTarget | null) =>
  handleValue(target, (number) => emit('value', number))

const handleInput = (target: EventTarget | null) =>
  handleValue(target, (number) => emit('input', number))

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

const reset = () => {
  inputRef.value?.blur()
  emit('reset')
  emit('active-key', null)
  emit('range-key', null)
  emit('pick-key', null)
  pickMode.value = false
  rangeMode.value = false
}

const onPressEsc = () => {
  reset()
}

const onPressEnter = () => {
  reset()
}

const handleKeyDown = (event: KeyboardEvent) => {
  if (props.disabled) return
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
  <Tooltip v-if="activeKey === cellKey">
    <label>
      <!-- TODO min/max validation display -->
      <input
        :ref="(element) => inputRef = element as HTMLInputElement"
        :value="value"
        class="editableCellInput"
        type="number"
        :max="max"
        @input="(event) => handleInput(event.target)"
        @change="(event) => handleChange(event.target)"
        @blur="(event) => {
          event.preventDefault()
          inputRef?.focus()
        }"
        @keydown.esc="onPressEsc"
        @keydown.enter="onPressEnter"
      />
    </label>
    <template #popper>
      <div>{{ singleDayTooltip }}</div>
      <div>Нажмите <InlineSVG :src="enterIcon" /> Enter, чтобы подтвердить изменения</div>
      <div>
        Нажмите <template v-if="macOS">
          ⎋ Esc
        </template>
        <template v-else>
          <InlineSVG :src="escapeIcon" /> Esc
        </template>, чтобы отменить их
      </div>
    </template>
  </Tooltip>
  <Tooltip v-else :delay="{ show: 2000 }">
    <button
      :ref="(element) => buttonRef = element as HTMLButtonElement"
      type="button"
      class="editableDataCell"
      :class="{ inRange }"
      :disabled="disabled"
      @click="handleButtonClick"
    >
      <slot />
    </button>
    <template #popper>
      <template v-if="activeKey !== null">
        <div>Зажмите <InlineSVG :src="shiftIcon" /> Shift и кликните, чтобы задать значения для всех дней от выбранного до этого.</div>
        <div>
          Зажмите <template v-if="macOS">
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
