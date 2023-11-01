<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import SelectableAirport from '../SelectableAirport.vue'

import { DetailsFormData } from './lib/types'

const emit = defineEmits<{
  (event: 'formCompleted', value: DetailsFormData): void
}>()

const formData = ref<DetailsFormData>({
  airportID: undefined,
})

const isValidForm = computed(() => !!formData.value.airportID)

watch(formData.value, () => {
  if (isValidForm.value) {
    emit('formCompleted', formData.value)
  }
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_airport" class="col-sm-5 col-form-label">Аэропорт</label>
    <div class="col-sm-7 d-flex align-items-center selected-airport-wrapper">
      <SelectableAirport
        id="form_data_airport"
        parent-element-class=".selected-airport-wrapper"
        @change="(value: number | undefined) => {
          formData.airportID = value
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
