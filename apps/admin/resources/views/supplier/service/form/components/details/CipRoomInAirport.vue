<script setup lang="ts">
import Select2BaseSelect from '~components/Select2BaseSelect.vue'
</script>

<template>
  <div class="row form-field field-bookingservicetype field-type field-required">
    <label for="form_data_type" class="col-sm-5 col-form-label">Аэропорт</label>
    <div class="col-sm-7 d-flex align-items-center selected-airport-wrapper">
      <Select2BaseSelect
        id="selected-airport"
        ref="airportSelect2"
        :options="[]"
        :value="undefined"
        parent=".selected-airport-wrapper"
        :enable-tags="true"
        required
        :show-empty-item="false"
        @input="(value: any) => {}"
      />
    </div>
  </div>
</template>

<style lang="scss" scoped>
.selected-airport-wrapper {
  position: relative;
}
</style>
