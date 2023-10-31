<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import SelectableCity from '../SelectableCity.vue'

import { DetailsFormData } from './lib/types'

const emit = defineEmits<{
  (event: 'getDetailsFormData', value: any): void
}>()

const formData = ref<DetailsFormData>({
  cityID: undefined,
})

const isValidForm = computed(() => !!formData.value.cityID)

watch(formData.value, () => {
  if (isValidForm.value) {
    emit('getDetailsFormData', formData.value)
  }
})

</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_type" class="col-sm-5 col-form-label">Город</label>
    <div class="col-sm-7 d-flex align-items-center selected-city-wrapper">
      <SelectableCity
        parent-element-class=".selected-city-wrapper"
        @change="(value: any) => {
          formData.cityID = value
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
