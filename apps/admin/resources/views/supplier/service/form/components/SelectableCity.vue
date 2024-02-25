<script setup lang="ts">
import { ref } from 'vue'

import { requestInitialData } from 'gts-common/helpers/initial-data'
import { z } from 'zod'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/shared/lib/constants'

import SelectComponent from '~components/SelectComponent.vue'

const { cities } = requestInitialData(z.object({
  cities: z.array(z.object({
    id: z.number(),
    name: z.string(),
    country_id: z.number(),
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

const selectedCityID = ref<number | undefined>(props.value)

const citiesOptions = mapEntitiesToSelectOptions(cities)

defineEmits<{
  (event: 'change', value: number | undefined): void
}>()

</script>

<template>
  <SelectComponent
    :name="name"
    :options="citiesOptions"
    required
    :value="selectedCityID"
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
