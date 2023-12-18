<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { onClickOutside, useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'
import { DateTime } from 'luxon'

import { parseAPIDateAndSetDefaultTime } from '~lib/date'
import { usePlatformDetect } from '~lib/platform'

import InlineIcon from '~components/InlineIcon.vue'

const props = withDefaults(defineProps<{
  value: string | undefined
  returnOnlyTime?: boolean
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
  returnOnlyTime: false,
})

const emit = defineEmits<{
  (event: 'change', value: string | null): void
}>()

const [isEditable, toggleEditable] = useToggle()
const { isMacOS } = usePlatformDetect()

const inputRef = ref<HTMLInputElement | null>(null)
const editedValue = ref<string>()
const isChanged = ref(false)

const localValue = computed<string>({
  get: () => {
    if (isChanged.value) {
      return editedValue.value || ''
    }
    return props.value ? DateTime.fromISO(props.value).toFormat('HH:mm') : ''
  },
  set: (value: string) => {
    editedValue.value = value
    isChanged.value = true
  },
})

const localDate = computed<string>(() => (props.value
  ? parseAPIDateAndSetDefaultTime(props.value).toFormat('yyyy-LL-dd') : ''))

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
  if (isEmptyValue) {
    emit('change', props.returnOnlyTime ? '00:00' : localDate.value)
  } else {
    emit('change', props.returnOnlyTime ? localValue.value : `${localDate.value} ${localValue.value}`)
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
    <a v-if="!isEditable && canEdit" href="#" @click.prevent="toggleEditable(true)">
      {{ displayValue }}
    </a>

    <span v-else-if="!canEdit">
      {{ displayValue }}
    </span>

    <Tooltip v-else theme="tooltip" handle-resize>
      <input
        ref="inputRef"
        v-model.trim="localValue"
        class="form-control editable-input w-100"
        :placeholder="placeholder"
        :required="required"
        type="time"
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
svg {
  width: auto;
  height: 1em;
  fill: currentcolor;
}

.editable-input {
  width: 5rem;
}
</style>
