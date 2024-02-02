<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { onClickOutside, useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'

import InlineIcon from '~components/InlineIcon.vue'

import { usePlatformDetect } from '~lib/platform'

const props = withDefaults(defineProps<{
  value: number | undefined
  placeholder?: string
  dimension?: string
  emptyValue?: string
  hideClickOutside?: boolean
  saveClickOutside?: boolean
  required?: boolean
  canEdit?: boolean
}>(), {
  placeholder: undefined,
  emptyValue: undefined,
  dimension: undefined,
  hideClickOutside: false,
  saveClickOutside: true,
  required: false,
  canEdit: true,
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
    return props.emptyValue || 'Не заполнено'
  }
  const dimensionText = props.dimension && localValue ? props.dimension : ''

  return `${localValue.value}${dimensionText}`
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

const applyEditable = () => {
  const isEmptyValue = String(localValue.value).trim().length === 0
  if (props.required && isEmptyValue && !inputRef.value?.reportValidity()) {
    return
  }
  if (isChanged.value) {
    emit('change', localValue.value)
    isChanged.value = false
  }
  toggleEditable(false)
}

const onPressEnter = () => {
  applyEditable()
}

const onPressEsc = () => {
  hideEditable()
}

if (props.hideClickOutside && !props.saveClickOutside) {
  onClickOutside(inputRef, hideEditable)
}

if (props.saveClickOutside && !props.hideClickOutside) {
  onClickOutside(inputRef, applyEditable)
}

</script>

<template>
  <div>
    <a v-if="!isEditable && canEdit" class="editable-input-value" href="#" @click.prevent="toggleEditable(true)">
      {{ displayValue }}
    </a>

    <span v-else-if="!canEdit" class="editable-input-value">
      {{ displayValue }}
    </span>

    <Tooltip v-else theme="tooltip" handle-resize>
      <input
        ref="inputRef"
        v-model.number="localValue"
        class="form-control editable-input"
        :placeholder="placeholder"
        :required="required"
        type="number"
        @keydown.esc="onPressEsc"
        @keydown.enter="onPressEnter"
      />
      <template #popper>
        <div class="exist-inline-icon">
          Нажмите
          <InlineIcon icon="subdirectory_arrow_left" /> Enter, чтобы подтвердить изменения
        </div>
        <div class="exist-inline-icon">
          Нажмите
          <template v-if="isMacOS">⎋ Esc</template>
          <template v-else>
            Esc
          </template>,
          чтобы отменить изменения и сбросить выделение
        </div>
      </template>
    </Tooltip>
  </div>
</template>

<style lang="scss" scoped>
.exist-inline-icon i {
  font-size: inherit;
}

.editable-input {
  width: 5rem;
}

.editable-input-value {
  line-height: 32px;
}
</style>
