<script lang="ts" setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'

import { onClickOutside } from '@vueuse/core'
import { Tooltip } from 'floating-vue'

type RangeError = (min: number, max: number) => string

const defaultRangeError: RangeError = (min, max) => `Введите от ${min} до ${max}`

const props = withDefaults(defineProps<{
  initValue: number | null
  value: number | null
  min: number
  max: number
  rangeError?: RangeError
  editable: boolean
  cellKey: string | null
  activeCellKey: string | null
  cellType: string | null
  activeCellType: string | null
  inRange: boolean | null
  rangeExist: boolean
  roomID: number
  dayMenuRef?: HTMLElement | null
  dayMenuActionCompleted?: boolean
}>(), {
  rangeError: (min, max) => `Введите от ${min} до ${max}`,
  dayMenuRef: null,
  dayMenuActionCompleted: false,
})

const emit = defineEmits<{
  (event: 'change', value: number | null): void
  (event: 'input', value: number | null): void
  (event: 'active-key', value: string | null, type: string | null): void
  (event: 'range-key', value: string | null): void
  (event: 'pick-key', value: string | null): void
  (event: 'context-menu', value: HTMLElement): void
  (event: 'reset'): void
}>()

const buttonRef = ref<HTMLButtonElement | null>(null)
const inputRef = ref<HTMLInputElement | null>(null)
const wrapperRef = ref<HTMLInputElement | null>(null)
const isChanged = ref(false)
const localValue = ref<number | null>(props.value)

const rangeMode = ref(false)
const pickMode = ref(false)

const errorMessage = ref<string | null>(null)

watch(() => props.value, (newValue) => {
  localValue.value = newValue
})

const handleInputMount = (input: HTMLInputElement | null) => {
  if (!input) return
  input.focus({ preventScroll: true })
  setTimeout(() => {
    input.select()
  }, 0)
}

watch(inputRef, (element: HTMLInputElement | null) => {
  if (element === null) return
  handleInputMount(element)
})

watch(() => props.dayMenuActionCompleted, (value) => {
  if (value === true && props.activeCellKey === props.cellKey) {
    isChanged.value = false
    emit('reset')
    emit('active-key', null, null)
    emit('range-key', null)
    emit('pick-key', null)
    pickMode.value = false
    rangeMode.value = false
  }
})

const transformInputValue = () => {
  const { min, max, rangeError } = props
  if (inputRef.value instanceof HTMLInputElement) {
    const inputValue = inputRef.value?.value
    const numValue = inputValue?.trim() === '' ? NaN : Number(inputValue)
    if (!isNaN(numValue)) {
      if (numValue >= min && numValue <= max) {
        errorMessage.value = null
        localValue.value = numValue
      } else {
        errorMessage.value = rangeError(min, max)
        localValue.value = numValue
      }
    } else {
      errorMessage.value = defaultRangeError(min, max)
      localValue.value = null
    }
  } else {
    errorMessage.value = defaultRangeError(min, max)
    localValue.value = null
  }
  emit('input', localValue.value)
}

const applyEditable = () => {
  const { min, max } = props
  if (localValue.value !== null && (localValue.value >= min && localValue.value <= max)
  && (props.initValue !== localValue.value || props.rangeExist)) {
    emit('change', localValue.value)
  }
}

const reset = () => {
  isChanged.value = false
  emit('reset')
  emit('active-key', null, null)
  emit('range-key', null)
  emit('pick-key', null)
  pickMode.value = false
  rangeMode.value = false
  errorMessage.value = null
}

const applyAndHideEditable = () => {
  if (!rangeMode.value && !pickMode.value) {
    applyEditable()
    reset()
  }
}

const handleKeyDown = (event: KeyboardEvent) => {
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
  if ((isInputRightClick || isButtonInRangeRightClick) && props.editable) {
    event.preventDefault()
    emit('context-menu', event.target as HTMLElement)
  }
}

const handleButtonClick = (event: MouseEvent) => {
  event.preventDefault()
  if ((rangeMode.value || pickMode.value) && props.cellType !== props.activeCellType && props.activeCellType !== null) {
    return
  }
  if (rangeMode.value) {
    emit('range-key', props.cellKey)
    return
  }
  if (pickMode.value) {
    emit('pick-key', props.cellKey)
    return
  }
  isChanged.value = !isChanged.value
  emit('active-key', props.cellKey, props.cellType)
}

onClickOutside(inputRef, (event: MouseEvent) => {
  if (props.dayMenuRef && props.dayMenuRef instanceof Element && event.target) {
    if (props.dayMenuRef.contains(event.target as Node)) {
      return
    }
  }
  applyAndHideEditable()
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
  <div
    v-if="!editable"
    class="quotas-cell no-editable"
    :style="{ height: isChanged ? '100%' : '100%' }"
    aria-hidden="true"
  >
    <a>
      <span v-if="value !== null">{{ value }}</span>
    </a>
  </div>
  <div
    v-else
    class="quotas-cell"
    :class="{ inRange }"
    :style="{ height: isChanged ? '100%' : '100%' }"
    aria-hidden="true"
  >
    <Tooltip
      v-if="activeCellKey === cellKey && editable"
      ref="wrapperRef"
      :theme="errorMessage === null ? 'tooltip' : 'danger-tooltip'"
      handle-resize
    >
      <input
        v-if="activeCellKey === cellKey"
        :ref="(element) => inputRef = element as HTMLInputElement"
        :value="value"
        class="form-control"
        @blur="(event) => {
          event.preventDefault()
          inputRef?.focus()
        }"
        @keydown.esc="reset"
        @keydown.enter="applyAndHideEditable"
        @input="transformInputValue"
      />
      <template #popper>
        <div v-if="errorMessage">{{ errorMessage }}</div>
        <template v-else>
          <div>Нажмите Enter, чтобы подтвердить изменения</div>
          <div>
            Нажмите Esc чтобы отменить изменения и сбросить выделение
          </div>
        </template>
      </template>
    </Tooltip>
    <button
      v-else
      :ref="(element) => buttonRef = element as HTMLButtonElement"
      type="button"
      @click.prevent="handleButtonClick"
      @mousedown.prevent
    >
      <template v-if="value !== null">{{ value }}</template>
      <template v-else>—</template>
    </button>
  </div>
</template>

<style lang="scss" scoped>
.quotas-cell {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  cursor: pointer;
  user-select: none;

  &.inRange {
    border: 1px solid rgba(blue, 0.3);
  }

  &:hover {
    background-color: #0000001a;
  }
}

button {
  width: 100%;
  height: 100%;
  border: none;
  background-color: transparent;
  outline: none;
}

input {
  height: 100%;
  border-radius: 0;
  font-size: 0.8125rem;
  line-height: 2rem;
  text-align: center;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  margin: 0;
  appearance: none;
}

input[type="number"] {
  appearance: textfield;
}

span {
  padding: 0 0.313rem;
}

.value-place-holder {
  color: #000;
  opacity: 0.6;
}

.no-editable {
  cursor: default;

  &:hover {
    background-color: transparent;
  }
}
</style>
