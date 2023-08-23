<script lang="ts" setup>
import { computed, ref } from 'vue'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import DateRangePicker from '~components/DateRangePicker.vue'
// import EditableInput from '~components/Editable/EditableNumberInput.vue'
import MultiSelect from '~components/MultiSelect.vue'

import { stringToNumber } from '../lib/convert'

const period = ref<[Date, Date]>()
const daysWeekSelected = ref<string[]>([])
const daysWeek = ref<any[]>([
  { value: 1, label: 'понедельник' },
  { value: 2, label: 'вторник' },
  { value: 3, label: 'среда' },
  { value: 4, label: 'четверг' },
  { value: 5, label: 'пятница' },
  { value: 6, label: 'суббота' },
  { value: 7, label: 'воскресенье' },
])
const price = ref<string>('')

const isValidForm = computed(() => stringToNumber(price.value)
&& period.value?.length === 2 && daysWeekSelected.value?.length > 0)
</script>

<template>
  <div class="form-labels form-inline">
    <div class="form-field field-daterange field-period field-required">
      <DateRangePicker
        id="period"
        label="Период"
        required
        :lock-periods="undefined"
        :editable-id="undefined"
        :min-date="undefined"
        :max-date="undefined"
        :value="period"
        @input="(dates) => period = dates"
      />
    </div>
    <div class="form-field field-select field-days field-required">
      <MultiSelect
        id="price-types"
        label="Выберите дни недели"
        :label-margin="false"
        required
        :value="daysWeekSelected"
        :options="daysWeek"
        @input="value => daysWeekSelected = value"
      />
    </div>
    <div class="form-field field-price field-required">
      <label for="price-filter">Цена</label>
      <input id="price-filter" v-model="price" required type="text" class="form-control">
    </div>
    <div class="form-field form-button">
      <BootstrapButton label="Обновить" size="small" severity="primary" :disabled="!isValidForm" />
    </div>
  </div>
</template>

<style lang="scss">
.form-inline {
  display: flex;
  flex-wrap: wrap;
  justify-content: flex-start;
  align-items: flex-end;
  margin-top: 1rem;

  .form-button {
    line-height: 21px;
  }

  .form-field {
    margin-right: 16px;
    margin-bottom: 16px;

    label {
      margin-bottom: 0;
      font-size: 10px;
    }

    input,
    .label {
      font-size: 11px;
    }
  }

  .field-period {
    width: 180px;

    input {
      text-align: right;
    }
  }

  .field-days {
    width: 260px;
  }

  .field-price {
    width: 90px;

    input {
      text-align: right;
    }
  }
}
</style>
