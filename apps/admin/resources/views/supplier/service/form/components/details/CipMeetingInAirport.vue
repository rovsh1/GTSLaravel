<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'

import SelectableAirport from '../SelectableAirport.vue'

import { DetailsFormData } from './lib/types'

const props = defineProps<{
  value: DetailsFormData | undefined
}>()

const emit = defineEmits<{
  (event: 'formCompleted', value: DetailsFormData | undefined): void
}>()

const formData = ref<DetailsFormData>(props.value || {
  airportId: undefined,
})

const isValidForm = computed(() => !!formData.value.airportId)

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

onMounted(() => {
  handleFormCompleted()
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_airport" class="col-sm-5 col-form-label">Аэропорт</label>
    <div class="col-sm-7 d-flex align-items-center selected-airport-wrapper">
      <SelectableAirport
        id="form_data_airport"
        name="data[data][airportId]"
        :value="formData.airportId"
        parent-element-class=".selected-airport-wrapper"
        @change="(value: number | undefined) => {
          formData.airportId = value
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
