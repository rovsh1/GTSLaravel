<script setup lang="ts">

import { computed } from 'vue'

import { Time, TimePeriod } from '~api/hotel/markup-settings'

const props = withDefaults(defineProps<{
  from: Time | null
  to: Time | null
  freePeriods: TimePeriod[]
  disabled?: boolean
  required?: boolean
  isEditMode?: boolean
  defaultMin?: Time
  defaultMax?: Time
}>(), {
  isEditMode: false,
  disabled: false,
  required: false,
  defaultMin: '00:00',
  defaultMax: '24:00',
})

const emit = defineEmits<{
  (event: 'update:from', value: Time | null): void
  (event: 'update:to', value: Time | null): void
}>()

type TimeHour = `${number}` | `${number}${number}`

const isEditMode = computed(() => props.isEditMode)
const defaultMin = computed(() => props.defaultMin)
const defaultMax = computed(() => props.defaultMax)

const timeOptions = computed(() => {
  const options: Time[] = []
  for (let i = 0; i <= 24; i++) {
    let hour: TimeHour = `${i}`
    if (i < 10) {
      hour = `0${i}`
    }
    const hourTime: Time = `${hour}:00`
    options.push(hourTime)
    const halfHourTime: Time = `${hour}:30`
    if (i !== 24) {
      options.push(halfHourTime)
    }
  }
  return options
})
const freePeriods = computed(() => props.freePeriods)
const from = computed({
  get: () => props.from,
  set: (value: Time | null) => emit('update:from', value),
})
const to = computed({
  get: () => props.to,
  set: (value: Time | null) => emit('update:to', value),
})

const isTimeWithinFreePeriod = (time: Time, isStrictTo: boolean = false) => {
  if (isEditMode.value) {
    return time >= defaultMin.value && (isStrictTo ? time < defaultMax.value : time <= defaultMax.value)
  }
  if (isStrictTo) {
    return Boolean(freePeriods.value?.find((period) => time >= period.from && time < period.to))
  }
  return Boolean(freePeriods.value?.find((period) => time >= period.from && time <= period.to))
}

const availableFromTimeOptions = computed(() => {
  const availableFromTimes: Time[] = []
  timeOptions.value.forEach((time) => {
    if (time <= '24:00' && isTimeWithinFreePeriod(time, true)) {
      availableFromTimes.push(time)
    }
  })

  return availableFromTimes
})

const availableToTimeOptions = computed(() => {
  const availableToTimes: Time[] = []
  timeOptions.value.forEach((time) => {
    if (time > '00:00' && (from.value && time > from.value) && isTimeWithinFreePeriod(time)) {
      availableToTimes.push(time)
    }
  })

  return availableToTimes
})

</script>

<template>
  <div :class="{ 'field-required': required }" class="col-md-6">
    <label for="from">С</label>
    <select
      id="from"
      v-model="from"
      class="form-select form-control"
      :disabled="disabled"
      :required="required"
    >
      <option :value="null" />
      <option v-for="item in availableFromTimeOptions" :key="item">{{ item }}</option>
    </select>
  </div>

  <div :class="{ 'field-required': required }" class="col-md-6">
    <label for="to">До</label>
    <select
      id="to"
      v-model="to"
      class="form-select form-control"
      :disabled="disabled"
      :required="required"
    >
      <option :value="null" />
      <option v-for="item in availableToTimeOptions" :key="item">{{ item }}</option>
    </select>
  </div>
</template>
