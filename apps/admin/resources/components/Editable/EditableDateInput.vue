<script setup lang="ts">

import { computed, ref, watch } from 'vue'
import InlineSVG from 'vue-inline-svg'

import escapeIcon from '@mdi/svg/svg/keyboard-esc.svg'
import enterIcon from '@mdi/svg/svg/keyboard-return.svg'
import { useToggle } from '@vueuse/core'
import { Tooltip } from 'floating-vue'
import { DateTime } from 'luxon'
import { nanoid } from 'nanoid'

import { usePlatformDetect } from '~lib/platform'

import DateRangePicker from '~components/DateRangePicker.vue'

const props = withDefaults(defineProps<{
  value: Date | undefined
  emptyValue?: string
  hideClickOutside?: boolean
  saveClickOutside?: boolean
  canEdit?: boolean
}>(), {
  emptyValue: undefined,
  hideClickOutside: false,
  saveClickOutside: true,
  canEdit: true,
})

const emit = defineEmits<{
  (event: 'change', value: Date): void
}>()

const [isEditable, toggleEditable] = useToggle()
const { isMacOS } = usePlatformDetect()

const dateElementID = `${nanoid()}_date`

const inputRef = ref<HTMLInputElement | null>(null)
const editedValue = ref<Date>()
const isChanged = ref(false)

const localValue = computed<Date | undefined>({
  get: () => (isChanged.value ? editedValue.value : props.value),
  set: (value: Date | undefined) => {
    editedValue.value = value
    isChanged.value = true
  },
})

const displayValue = computed(() => {
  if (!localValue.value) {
    return props.emptyValue || 'Не установлено'
  }
  const displayDate = DateTime.fromJSDate(localValue.value)

  return `${displayDate.toFormat('dd-LL-yyyy')}`
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
  emit('change', localValue.value)
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
        <div class="exist-svg">
          Нажмите
          <InlineSVG :src="enterIcon" /> Enter, чтобы подтвердить изменения
        </div>
        <div class="exist-svg">
          Нажмите
          <template v-if="isMacOS">⎋ Esc</template>
          <template v-else>
            <InlineSVG :src="escapeIcon" /> Esc
          </template>,
          чтобы отменить изменения и сбросить выделение
        </div>
      </template>
    </Tooltip>
  </div>
</template>

<style lang="scss">
.exist-svg svg {
  width: auto;
  height: 1em;
  fill: currentcolor;
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
