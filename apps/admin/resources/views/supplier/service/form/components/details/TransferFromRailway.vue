<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { useRailwayStationSearchAPI } from '~api/railway-station'

import Select2BaseSelect from '~components/Select2BaseSelect.vue'

import SelectableCity from '../SelectableCity.vue'

import { DetailsFormData } from './lib/types'

const emit = defineEmits<{
  (event: 'formCompleted', value: DetailsFormData): void
}>()

const formData = ref<DetailsFormData>({
  cityID: undefined,
  railwayStationId: undefined,
})

const { isFetching: isFetchingRailwayStation, data: railwayStation, execute: fetchRailwayStation } = useRailwayStationSearchAPI(
  computed(() => ({
    cityID: formData.value.cityID,
  })),
)

const railwayStationOptions = computed(() => mapEntitiesToSelectOptions(railwayStation.value?.map((station) => ({
  id: station.id,
  name: station.name,
})) || []))

const isValidForm = computed(() => !!formData.value.cityID && !!formData.value.railwayStationId)

watch(formData.value, () => {
  if (isValidForm.value) {
    emit('formCompleted', formData.value)
  }
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_city" class="col-sm-5 col-form-label">Город</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-wrapper">
      <SelectableCity
        id="form_data_city"
        parent-element-class=".selected-city-wrapper"
        @change="(value: number | undefined) => {
          formData.cityID = value
          fetchRailwayStation()
        }"
      />
    </div>
  </div>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_railway_station" class="col-sm-5 col-form-label">Жд станция</label>
    <div class="col-sm-7 d-flex align-items-center selected-railway-station-wrapper">
      <Select2BaseSelect
        id="form_data_railway_station"
        :options="railwayStationOptions"
        :value="formData.railwayStationId"
        parent=".selected-railway-station-wrapper"
        :enable-tags="true"
        required
        :disabled-placeholder="isFetchingRailwayStation ? 'Загрузка' : ''"
        :disabled="!formData.cityID || isFetchingRailwayStation"
        :show-empty-item="false"
        @input="(value: any) => {
          formData.railwayStationId = value ? Number(value) : undefined
        }"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
