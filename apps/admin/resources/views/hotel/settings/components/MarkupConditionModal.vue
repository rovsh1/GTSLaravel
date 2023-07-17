<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { MarkupCondition, Time, TimePeriod } from '~api/hotel/markup-settings'

import BaseDialog from '~components/BaseDialog.vue'

const props = withDefaults(defineProps<{
  value?: MarkupCondition
  opened: MaybeRef<boolean>
  title: string
  loading?: MaybeRef<boolean>
  min?: Time
  max?: Time
  freePeriods: TimePeriod[]
}>(), {
  loading: false,
  value: undefined,
  min: undefined,
  max: undefined,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', value: MarkupCondition): void
}>()

const localValue = computed(() => props.value)
const from = ref<Time>()
const to = ref<Time>()
const percent = ref<number>()

// const minFrom = computed(() => props.min)
// const maxFrom = computed(() => props.max)
// const minTo = computed(() => {
//   if (from.value && !props.min) {
//     return from.value
//   }
//   if (from.value && props.min && from.value > props.min) {
//     return from.value
//   }
//   return props.min
// })
// const maxTo = computed(() => props.max)
const freePeriods = computed(() => props.freePeriods)

watch(localValue, (markupCondition) => {
  if (!markupCondition) {
    return
  }
  from.value = markupCondition.from
  to.value = markupCondition.to
  percent.value = markupCondition.percent
})

const clearForm = () => {
  from.value = undefined
  to.value = undefined
  percent.value = undefined
}

const closeModal = () => {
  clearForm()
  emit('close')
}

const markupConditionModalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!markupConditionModalForm.value?.reportValidity()) {
    return
  }
  if (!from.value || !to.value || !percent.value) {
    return
  }
  const payload: MarkupCondition = {
    from: from.value,
    to: to.value,
    percent: percent.value,
  }

  emit('submit', payload)
  clearForm()
}

const items = computed<string[]>(() => {
  const options = []
  for (let i = 0; i <= 24; i++) {
    let hour = `${i}`
    if (i < 10) {
      hour = `0${i}`
    }
    const hourTime = `${hour}:00`
    options.push(hourTime)

    const halfHourTime = `${hour}:30`
    if (i !== 24) {
      options.push(halfHourTime)
    }
  }
  return options
})
const freeItems = computed(() => {
  const free: string[] = []
  items.value.forEach((time) => {
    freePeriods.value.forEach((period) => {
      if (time >= period.from && time <= period.to) {
        free.push(time)
      }
    })
  })

  return free
})

const selectedItemsPrev = ref<string[]>([])
const selectedItems = ref<string[]>([])
const startSelectionFrom = ref()

const updateSelection = (index: any) => {
  if (startSelectionFrom.value === null) {
    return
  }
  const value = items.value[index]
  if (!freeItems.value.includes(value)) {
    return
  }

  const iMin = Math.min(startSelectionFrom.value, index)
  const iMax = Math.max(startSelectionFrom.value, index)

  selectedItems.value = Array.from(
    [
      ...selectedItemsPrev.value,
      ...items.value.slice(iMin, iMax + 1),
    ].reduce((acc, n) => acc.set(n, (acc.get(n) ?? 0) + 1), new Map()),
  )
    .filter((n) => n[1] === 1)
    .map((n) => n[0])
}

const startSelection = (index: any) => {
  const value = items.value[index]
  if (!freeItems.value.includes(value)) {
    return
  }
  selectedItemsPrev.value = [...selectedItems.value]
  startSelectionFrom.value = index
  updateSelection(index)
}

const stopSelection = () => {
  startSelectionFrom.value = null
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading as boolean"
    @close="closeModal"
  >
    <template #title>{{ title }}</template>

    <form ref="markupConditionModalForm" class="row g-3" @submit.prevent="onModalSubmit">
      <div class="field-required">
        <label>
          Период
          <input type="hidden">
        </label>
        <div
          class="d-grid column-gap-2 row-gap-1"
          style="grid-template-columns: 2fr 2fr 2fr 2fr 2fr 2fr 2fr;"
          @mouseleave="stopSelection"
          @blur="stopSelection"
        >
          <div
            v-for="(item, idx) in items"
            :key="item"
            class="text-center time-block"
            :class="{ selected: selectedItems.includes(item), disabled: !freeItems.includes(item) }"
            @mousedown="startSelection(idx)"
            @mouseenter="updateSelection(idx)"
            @focusin="updateSelection(idx)"
            @mouseup="stopSelection"
          >
            {{ item }}
          </div>
        </div>
      </div>

      <!--      <div class="col-md-6">-->
      <!--        <TimeSelect-->
      <!--          id="from"-->
      <!--          v-model="from"-->
      <!--          label="Начало"-->
      <!--          :min="minFrom"-->
      <!--          :max="maxFrom"-->
      <!--          required-->
      <!--        />-->
      <!--      </div>-->
      <!--      <div class="col-md-6">-->
      <!--        <TimeSelect-->
      <!--          id="to"-->
      <!--          v-model="to"-->
      <!--          label="Конец"-->
      <!--          :min="minTo"-->
      <!--          :max="maxTo"-->
      <!--          required-->
      <!--        />-->
      <!--      </div>-->
      <div class="col-md-12 field-required">
        <label for="markup">Наценка</label>
        <input
          id="markup"
          v-model="percent"
          type="number"
          class="form-control"
          required
        >
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>

<style scoped lang="scss">
.time-block {
  display: inline-block;
  padding: 0.2rem;
  border: var(--bs-border-width) solid var(--bs-border-color);
  background-color: white;
  color: var(--bs-secondary-color);
  user-select: none;
  -webkit-touch-callout: none;

  &:hover {
    background-color: rgba(0, 123, 255, 50%);
    color: white;
  }

  &.selected {
    background-color: var(--bs-primary);
    color: white;
  }

  &.disabled {
    background-color: var(--bs-secondary-bg);
    color: gray;
    opacity: 0.5;
    cursor: not-allowed;
  }
}
</style>
