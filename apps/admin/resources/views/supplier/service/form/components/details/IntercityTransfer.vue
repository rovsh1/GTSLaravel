<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import BootstrapCheckbox from '~components/Bootstrap/BootstrapCheckbox.vue'

import SelectableCity from '../SelectableCity.vue'

import { DetailsFormData } from './lib/types'

const emit = defineEmits<{
  (event: 'formCompleted', value: DetailsFormData): void
}>()

const formData = ref<DetailsFormData>({
  fromCityId: undefined,
  toCityId: undefined,
  returnTripIncluded: false,
})

const isValidForm = computed(() => !!formData.value.fromCityId && !!formData.value.toCityId)

watch(formData.value, () => {
  if (isValidForm.value) {
    emit('formCompleted', formData.value)
  }
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_city_from" class="col-sm-5 col-form-label">Из города</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-from-wrapper">
      <SelectableCity
        id="form_data_city_from"
        parent-element-class=".selected-city-from-wrapper"
        @change="(value: number | undefined) => {
          formData.fromCityId = value
        }"
      />
    </div>
  </div>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_city_to" class="col-sm-5 col-form-label">В город</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-to-wrapper">
      <SelectableCity
        id="form_data_city_to"
        parent-element-class=".selected-city-to-wrapper"
        @change="(value: number | undefined) => {
          formData.toCityId = value
        }"
      />
    </div>
  </div>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_return_trip" class="col-sm-5 col-form-label">Обратная поездка включена</label>
    <div class="col-sm-7 d-flex align-items-center selected-return-trip-wrapper">
      <BootstrapCheckbox
        id="form_data_return_trip"
        :value="(formData.returnTripIncluded as boolean)"
        :disabled="false"
        label=""
        @input="(value) => formData.returnTripIncluded = value"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
