<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'

import { z } from 'zod'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { useAirportSearchAPI } from '~api/airport'

import { requestInitialData } from '~lib/initial-data'

import { SelectOption } from '~components/Bootstrap/lib'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const { airports, supplier, cities } = requestInitialData('view-initial-data-supplier', z.object({
  supplier: z.object({
    id: z.number(),
    name: z.string(),
  }),
  airports: z.array(z.object({
    id: z.number(),
    name: z.string(),
  })),
  cities: z.array(z.object({
    id: z.number(),
    name: z.string(),
    country_id: z.number(),
  })),
}))

const props = withDefaults(defineProps<{
  aiportsFromCity?: number
  parentElementClass: string
  disabled?: boolean
}>(), {
  aiportsFromCity: undefined,
  disabled: false,
})

const airPortsOptions = ref<SelectOption[]>([])

const { isFetching: isFetchingAirports, data: airPorts, execute: fetchAirPorts } = useAirportSearchAPI(computed(() =>
  ({ cityID: props.aiportsFromCity })))

const handleFetchAirPorts = async () => {
  await fetchAirPorts()
  airPortsOptions.value = mapEntitiesToSelectOptions(airPorts.value?.map((airPort) => ({
    id: airPort.id,
    name: airPort.name,
  })) || [])
}

watch(() => props.aiportsFromCity, async () => {
  await handleFetchAirPorts()
})

onMounted(async () => {
  await handleFetchAirPorts()
})

</script>

<template>
  <Select2BaseSelect
    id="selected-airport"
    ref="airportSelect2"
    :options="airPortsOptions"
    :value="undefined"
    :parent="parentElementClass"
    :enable-tags="true"
    required
    :disabled="disabled || isFetchingAirports"
    :show-empty-item="false"
    @input="(value: any) => {}"
  />
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
