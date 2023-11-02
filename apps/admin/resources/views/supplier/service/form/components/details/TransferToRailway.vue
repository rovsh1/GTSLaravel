<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { useRailwayStationSearchAPI } from '~api/railway-station'

import Select2BaseSelect from '~components/Select2BaseSelect.vue'

import SelectableCity from '../SelectableCity.vue'

import { DetailsFormData } from './lib/types'

const props = defineProps<{
  value: DetailsFormData | undefined
}>()

const emit = defineEmits<{
  (event: 'formCompleted', value: DetailsFormData | undefined): void
}>()

const formData = ref<DetailsFormData>(props.value || {
  cityId: undefined,
  railwayStationId: undefined,
})

const { isFetching: isFetchingRailwayStation, data: railwayStation, execute: fetchRailwayStation } = useRailwayStationSearchAPI(
  computed(() => ({
    cityID: formData.value.cityId,
  })),
)

const railwayStationOptions = computed(() => mapEntitiesToSelectOptions(railwayStation.value?.map((station) => ({
  id: station.id,
  name: station.name,
})) || []))

const isValidForm = computed(() => !!formData.value.cityId && !!formData.value.railwayStationId)

const handleFormCompleted = () => {
  if (isValidForm.value) {
    emit('formCompleted', formData.value)
  } else {
    emit('formCompleted', undefined)
  }
}

watch(formData.value, () => {
  handleFormCompleted()
})

onMounted(async () => {
  if (props.value) {
    await fetchRailwayStation()
  }
  handleFormCompleted()
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_city" class="col-sm-5 col-form-label">Город</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-wrapper">
      <SelectableCity
        id="form_data_city"
        name="data[data][cityId]"
        :value="formData.cityId"
        parent-element-class=".selected-city-wrapper"
        @change="(value: number | undefined) => {
          formData.cityId = value
          formData.railwayStationId = undefined
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
        name="data[data][railwayStationId]"
        :options="railwayStationOptions"
        :value="formData.railwayStationId"
        parent=".selected-railway-station-wrapper"
        required
        :disabled-placeholder="isFetchingRailwayStation ? 'Загрузка' : 'Выберите город'"
        :disabled="!formData.cityId || isFetchingRailwayStation"
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
