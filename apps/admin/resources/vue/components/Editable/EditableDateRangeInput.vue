<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'
import { DateTime } from 'luxon'
import { nanoid } from 'nanoid'

import DateRangePicker from '~components/DateRangePicker.vue'
import InlineIcon from '~components/InlineIcon.vue'

import { formatDateTimeToAPIDateTime, formatDateToAPIDate, parseAPIDateAndSetDefaultTime } from '~helpers/date'
import { usePlatformDetect } from '~helpers/platform'

type DatePeriod = {
  dateFrom: string
  dateTo: string
}

const props = withDefaults(defineProps<{
  value: DatePeriod | undefined
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
  (event: 'change', value: DatePeriod): void
}>()

const [isEditable, toggleEditable] = useToggle()
const { isMacOS } = usePlatformDetect()

const dateElementID = `${nanoid()}_dateRange`

const inputRef = ref<HTMLInputElement | null>(null)
const editedValue = ref<[Date, Date]>()
const isChanged = ref(false)

const localValue = computed<[Date, Date] | undefined>({
  get: () => {
    if (isChanged.value) {
      return editedValue.value
    }
    return props.value
      ? [parseAPIDateAndSetDefaultTime(props.value.dateFrom).toJSDate(),
        parseAPIDateAndSetDefaultTime(props.value.dateTo).toJSDate()] as [Date, Date]
      : undefined
  },
  set: (value: [Date, Date] | undefined) => {
    editedValue.value = value
    isChanged.value = true
  },
})

const localTime = computed<string | undefined>(() => (props.value ? DateTime.fromISO(props.value.dateFrom).toFormat('HH:mm') : '00:00'))

const displayValue = computed(() => {
  if (!localValue.value) {
    return props.emptyValue || 'Не заполнено'
  }
  const displayDateFrom = DateTime.fromJSDate(localValue.value[0])
  const displayDateTo = DateTime.fromJSDate(localValue.value[1])

  return `${displayDateFrom.toFormat('dd.LL.yyyy')} - ${displayDateTo.toFormat('dd.LL.yyyy')}`
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

  emit('change', {
    dateFrom: props.returnOnlyDate ? formatDateToAPIDate(localValue.value[0])
      : formatDateTimeToAPIDateTime(`${formatDateToAPIDate(localValue.value[0])} ${localTime.value}`),
    dateTo: props.returnOnlyDate ? formatDateToAPIDate(localValue.value[1])
      : formatDateTimeToAPIDateTime(`${formatDateToAPIDate(localValue.value[0])} ${localTime.value}`),
  })
  isChanged.value = false
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
          :set-input-focus="true"
          @input="(dates) => {
            localValue = dates as [Date, Date]
            onPressEnter()
          }"
          @click-outside="onClickOutsideHandler"
          @press-esc="onPressEsc"
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
</style>
