<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import BootstrapCheckbox from '~components/Bootstrap/BootstrapCheckbox.vue'

import SelectableCity from '../SelectableCity.vue'

import { DetailsFormData } from './lib/types'

const emit = defineEmits<{
  (event: 'getDetailsFormData', value: any): void
}>()

const formData = ref<DetailsFormData>({
  fromCityId: undefined,
  toCityId: undefined,
  returnTripIncluded: false,
})

const isValidForm = computed(() => !!formData.value.fromCityId && !!formData.value.toCityId)

watch(formData.value, () => {
  if (isValidForm.value) {
    emit('getDetailsFormData', formData.value)
  }
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_type" class="col-sm-5 col-form-label">Из города</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-from-wrapper">
      <SelectableCity
        parent-element-class=".selected-city-from-wrapper"
        @change="(value: any) => {
          formData.fromCityId = value
        }"
      />
    </div>
  </div>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_type" class="col-sm-5 col-form-label">В город</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-to-wrapper">
      <SelectableCity
        parent-element-class=".selected-city-to-wrapper"
        @change="(value: any) => {
          formData.toCityId = value
        }"
      />
    </div>
  </div>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_type" class="col-sm-5 col-form-label">Обратная поездка включена</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-to-wrapper">
      <BootstrapCheckbox
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
