<script setup lang="ts">
import { ref } from 'vue'

import { nanoid } from 'nanoid'
import { z } from 'zod'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { requestInitialData } from '~lib/initial-data'

import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const id = `selected-airport-${nanoid()}`

const selectedCityID = ref<number | undefined>()

const { cities } = requestInitialData('view-initial-data-supplier', z.object({
  cities: z.array(z.object({
    id: z.number(),
    name: z.string(),
    country_id: z.number(),
  })),
}))

withDefaults(defineProps<{
  parentElementClass: string
  value?: number
  disabled?: boolean
}>(), {
  value: undefined,
  disabled: false,
})

const citiesOptions = mapEntitiesToSelectOptions(cities)

defineEmits<{
  (event: 'change', value: number | undefined): void
}>()

</script>

<template>
  <Select2BaseSelect
    :id="id"
    :options="citiesOptions"
    :value="selectedCityID"
    :parent="parentElementClass"
    :enable-tags="true"
    required
    :disabled="disabled"
    :show-empty-item="false"
    @input="(value: any) => {
      $emit('change', value ? Number(value) : undefined)
    }"
  />
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
