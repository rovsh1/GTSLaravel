<script setup lang="ts">
import { onMounted, ref } from 'vue'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { useAirportSearchAPI } from '~api/airport'

import { SelectOption } from '~components/Bootstrap/lib'

import SelectableAirport from '../SelectableAirport.vue'

const { data: airPorts, execute: fetchAirPorts } = useAirportSearchAPI({})

const airPortsOptions = ref<SelectOption[]>([])

onMounted(async () => {
  await fetchAirPorts()
  airPortsOptions.value = mapEntitiesToSelectOptions(airPorts.value?.map((airPort) => ({
    id: airPort.id,
    name: airPort.name,
  })) || [])
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_type" class="col-sm-5 col-form-label">Аэропорт</label>
    <div class="col-sm-7 d-flex align-items-center selected-airport-wrapper">
      <SelectableAirport
        parent-element-class=".selected-airport-wrapper"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
