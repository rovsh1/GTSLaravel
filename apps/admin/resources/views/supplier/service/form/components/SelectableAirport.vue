<script setup lang="ts">
import { ref } from 'vue'

import { z } from 'zod'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { requestInitialData } from '~lib/initial-data'

import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const { airports } = requestInitialData('view-initial-data-supplier-service', z.object({
  airports: z.array(z.object({
    id: z.number(),
    name: z.string(),
  })),
}))

const props = withDefaults(defineProps<{
  id: string
  parentElementClass: string
  value?: number
  disabled?: boolean
}>(), {
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
  <Select2BaseSelect
    :id="id"
    :options="airPortsOptions"
    :value="selectedAirportID"
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
