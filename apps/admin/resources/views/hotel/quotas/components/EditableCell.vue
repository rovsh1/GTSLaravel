<script lang="ts" setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'

import { Tooltip } from 'floating-vue'

import { isMacOS } from '~resources/lib/platform'

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
}>()

const inputRef = ref<HTMLInputElement | null>(null)

const rangeMode = ref(false)
const pickMode = ref(false)

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

const pickModifier = computed(() => (isMacOS() ? '⌘' : 'Ctrl'))

const keydown = (event: KeyboardEvent) => {
  if (event.shiftKey) rangeMode.value = true
  if (event.ctrlKey || event.metaKey) pickMode.value = true
}

const keyup = () => {
  rangeMode.value = false
  pickMode.value = false
}

onMounted(() => {
  window.addEventListener('keydown', keydown)
  window.addEventListener('keyup', keyup)
})

onUnmounted(() => {
  window.removeEventListener('keydown', keydown)
  window.removeEventListener('keyup', keyup)
})
</script>
<template>
  <label v-if="activeKey === cellKey">
    <input
      :ref="(element) => inputRef = element as HTMLInputElement"
      :value="value"
      class="editableCellInput"
      type="number"
      :max="max"
      @input="(event) => handleInput(event.target)"
      @change="(event) => handleChange(event.target)"
      @blur="(event) => {
        if (rangeMode || pickMode) {
          event.preventDefault()
          inputRef?.focus()
          return
        }
        emit('active-key', null)
        emit('range-key', null)
        emit('pick-key', null)
      }"
      @keydown.esc="inputRef?.blur()"
      @keydown.enter="inputRef?.blur()"
    />
  </label>
  <Tooltip v-else :disabled="activeKey === null" :delay="{ show: 2000 }">
    <button
      type="button"
      class="editableDataCell"
      :class="{ inRange }"
      :disabled="disabled"
      @click="handleButtonClick"
    >
      <slot />
    </button>
    <template #popper>
      <div>Зажмите Shift и кликните, чтобы задать значения для всех дней от выбранного до этого.</div>
      <div>Зажмите {{ pickModifier }} и кликните, чтобы добавить день в выборку или удалить из неё.</div>
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
</style>
