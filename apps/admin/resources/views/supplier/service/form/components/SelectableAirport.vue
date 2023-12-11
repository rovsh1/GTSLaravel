<script setup lang="ts">
import { ref } from 'vue'

import { z } from 'zod'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/shared/lib/constants'

import { requestInitialData } from '~lib/initial-data'

import SelectComponent from '~components/SelectComponent.vue'

const { airports } = requestInitialData(z.object({
  airports: z.array(z.object({
    id: z.number(),
    name: z.string(),
  })),
}))

const props = withDefaults(defineProps<{
  id: string
  parentElementClass: string
  name?: string
  value?: number
  disabled?: boolean
}>(), {
  name: undefined,
  value: undefined,
  disabled: false,
})

const selectedAirportID = ref<number | undefined>(props.value)

const airPortsOptions = mapEntitiesToSelectOptions(airports)

defineEmits<{
  (event: 'change', value: number | undefined): void
}>()

</script>

<template>
  <SelectComponent
    :name="name"
    :options="airPortsOptions"
    required
    :value="selectedAirportID"
    :enable-tags="true"
    :disabled="disabled"
    @change="(value) => {
      $emit('change', value ? Number(value) : undefined)
    }"
  />
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
