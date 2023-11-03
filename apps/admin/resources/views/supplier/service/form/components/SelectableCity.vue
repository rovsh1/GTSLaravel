<script setup lang="ts">
import { ref } from 'vue'

import { z } from 'zod'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/shared/lib/constants'

import { requestInitialData } from '~lib/initial-data'

import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const { cities } = requestInitialData('view-initial-data-supplier-service', z.object({
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
  <Select2BaseSelect
    :id="id"
    :name="name"
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
