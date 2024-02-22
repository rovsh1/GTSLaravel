<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'

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
})

const isValidForm = computed(() => !!formData.value.cityId)

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
    <label for="form_data_airport" class="col-sm-5 col-form-label">Город</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-wrapper">
      <SelectableCity
        id="form_data_airport"
        name="data[data][cityId]"
        :value="formData.cityId"
        parent-element-class=".selected-city-wrapper"
        @change="(value: number | undefined) => {
          formData.cityId = value
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
