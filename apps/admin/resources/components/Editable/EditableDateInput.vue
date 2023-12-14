<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'
import { DateTime } from 'luxon'
import { nanoid } from 'nanoid'

import { formatDateToAPIDate, parseAPIDateAndSetDefaultTime } from '~lib/date'
import { usePlatformDetect } from '~lib/platform'

import DateRangePicker from '~components/DateRangePicker.vue'
import InlineIcon from '~components/InlineIcon.vue'

const props = withDefaults(defineProps<{
  value: string | undefined
  returnOnlyDate?: boolean
  emptyValue?: string
  hideClickOutside?: boolean
  saveClickOutside?: boolean
  canEdit?: boolean
}>(), {
  emptyValue: undefined,
  hideClickOutside: false,
  saveClickOutside: true,
  canEdit: true,
  returnOnlyDate: false,
})

const emit = defineEmits<{
  (event: 'change', value: string): void
}>()

const [isEditable, toggleEditable] = useToggle()
const { isMacOS } = usePlatformDetect()

const dateElementID = `${nanoid()}_date`

const inputRef = ref<HTMLInputElement | null>(null)
const editedValue = ref<Date>()
const isChanged = ref(false)

const localValue = computed<Date | undefined>({
  get: () => {
    if (isChanged.value) {
      return editedValue.value
    }
    return props.value
      ? parseAPIDateAndSetDefaultTime(props.value).toJSDate()
      : undefined
  },
  set: (value: Date | undefined) => {
    editedValue.value = value
    isChanged.value = true
  },
})

const localTime = computed<string | undefined>(() =>
  (props.value ? DateTime.fromISO(props.value).toFormat('HH:mm') : '00:00'))

const displayValue = computed(() => {
  if (!localValue.value) {
    return props.emptyValue || 'Не заполнено'
  }
  const displayDate = DateTime.fromJSDate(localValue.value)

  return `${displayDate.toFormat('dd.LL.yyyy')}`
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
  if (!localValue.value) {
    toggleEditable(false)
    return
  }
  emit('change', props.returnOnlyDate ? formatDateToAPIDate(localValue.value)
    : `${formatDateToAPIDate(localValue.value)} ${localTime.value}`)
  toggleEditable(false)
}

const onPressEnter = () => {
  applyEditable()
}

const onPressEsc = () => {
  hideEditable()
}

const onClickOutsideHandler = () => {
  if (props.hideClickOutside && !props.saveClickOutside) {
    hideEditable()
  }
  if (props.saveClickOutside && !props.hideClickOutside) {
    applyEditable()
  }
}

</script>

<template>
  <div style="position: relative;">
    <a v-if="!isEditable && canEdit" href="#" @click.prevent="toggleEditable(true)">
      {{ displayValue }}
    </a>

    <span v-else-if="!canEdit">
      {{ displayValue }}
    </span>

    <Tooltip v-else theme="tooltip" handle-resize>
      <div>
        <DateRangePicker
          :id="dateElementID"
          :value="localValue"
          :single-mode="true"
          :set-input-focus="true"
          @input="(date) => {
            localValue = date as Date
            onPressEnter()
          }"
          @click-outside="onClickOutsideHandler"
          @press-esc="onPressEsc"
          @press-enter="onPressEnter"
        />
      </div>
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

<style lang="scss">
.exist-inline-icon i {
  font-size: inherit;
}

.editable-input {
  width: 5rem;
}

/* stylelint-disable declaration-no-important */
.litepicker {
  top: 100% !important;
  left: 0 !important;
}
/* stylelint-enable declaration-no-important */
</style>
