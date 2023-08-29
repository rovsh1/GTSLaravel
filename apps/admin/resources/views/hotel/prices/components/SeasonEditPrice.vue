<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'

import { nanoid } from 'nanoid'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import DateRangePicker from '~components/DateRangePicker.vue'
// import EditableInput from '~components/Editable/EditableNumberInput.vue'
import MultiSelect from '~components/MultiSelect.vue'

import { stringToNumber } from '../lib/convert'

interface FormData {
  period: [Date, Date] | undefined
  daysWeekSelected: string[]
  price: string
}

const props = withDefaults(defineProps<{
  period?: [Date, Date]
  isFetching: boolean
}>(), {
  period: undefined,
})

const periodElementID = `${nanoid()}_period`
const priceTypesElementID = `${nanoid()}_price-types`
const priceElementID = `${nanoid()}_price-filter`

const daysWeek = ref<any[]>([
  { value: 1, label: 'понедельник' },
  { value: 2, label: 'вторник' },
  { value: 3, label: 'среда' },
  { value: 4, label: 'четверг' },
  { value: 5, label: 'пятница' },
  { value: 6, label: 'суббота' },
  { value: 7, label: 'воскресенье' },
])

const seasonFormData = reactive<FormData>({
  period: props.period,
  daysWeekSelected: daysWeek.value.map((day) => day.value),
  price: '',
})

const isValidForm = computed(() => stringToNumber(seasonFormData.price)
&& seasonFormData.period?.length === 2 && seasonFormData.daysWeekSelected?.length > 0)
</script>

<template>
  <div class="form-labels form-inline">
    <div class="form-field field-daterange field-period field-required">
      <DateRangePicker
        :id="periodElementID"
        label="Период"
        required
        :lock-periods="undefined"
        :editable-id="undefined"
        :min-date="undefined"
        :max-date="undefined"
        :value="seasonFormData.period"
        @input="(dates) => seasonFormData.period = dates"
      />
    </div>
    <div class="form-field field-select field-days field-required">
      <MultiSelect
        :id="priceTypesElementID"
        label="Выберите дни недели"
        :label-margin="false"
        required
        :value="seasonFormData.daysWeekSelected"
        :options="daysWeek"
        @input="value => seasonFormData.daysWeekSelected = value"
      />
    </div>
    <div class="form-field field-price field-required">
      <label :for="priceElementID">Цена</label>
      <input :id="priceElementID" v-model="seasonFormData.price" required type="text" class="form-control">
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
