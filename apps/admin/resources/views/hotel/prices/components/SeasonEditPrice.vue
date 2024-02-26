<script lang="ts" setup>
import { computed, reactive, watch } from 'vue'

import { formatDateToAPIDate, parseAPIDateToJSDate } from 'gts-common/helpers/date'
import { nanoid } from 'nanoid'

import { daysOfWeekOptions } from '~resources/js/config/constants'

import { updateRoomSeasonPricesBatch } from '~api/hotel/prices/seasons'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import DateRangePicker from '~components/DateRangePicker.vue'
import SelectComponent from '~components/SelectComponent.vue'

import { stringToNumber } from '../lib/convert'
import { PricesAccumulationData, SeasonPeriod, SeasonUpdateFormData } from '../lib/types'

const props = withDefaults(defineProps<{
  cellId?: string
  seasonPeriod: SeasonPeriod
  seasonData: PricesAccumulationData
}>(), {
  cellId: undefined,
})

const emit = defineEmits<{
  (event: 'updateSeasonDaysData', status: boolean): void
}>()

const periodElementID = `${nanoid()}_period`
const priceElementID = `${nanoid()}_price-filter`

const seasonFormData = reactive<SeasonUpdateFormData>({
  period: [parseAPIDateToJSDate(props.seasonPeriod.from), parseAPIDateToJSDate(props.seasonPeriod.to)],
  daysWeekSelected: daysOfWeekOptions.map((day) => day.value) as string[],
  price: '',
})

watch(() => props.cellId, () => {
  seasonFormData.period = [parseAPIDateToJSDate(props.seasonPeriod.from), parseAPIDateToJSDate(props.seasonPeriod.to)]
  seasonFormData.daysWeekSelected = daysOfWeekOptions.map((day) => day.value) as string[]
  seasonFormData.price = ''
})

const isValidForm = computed(() => stringToNumber(seasonFormData.price)
  && seasonFormData.period?.length === 2 && seasonFormData.daysWeekSelected?.length > 0)

const onSubmitUpdateData = async () => {
  if (!isValidForm.value) return
  emit('updateSeasonDaysData', true)
  const { data: updateStatusResponse } = await updateRoomSeasonPricesBatch({
    ...props.seasonData,
    price: Number(seasonFormData.price),
    date_from: formatDateToAPIDate(seasonFormData.period[0]),
    date_to: formatDateToAPIDate(seasonFormData.period[1]),
    week_days: seasonFormData.daysWeekSelected.map((str) => parseInt(str, 10)),
  })
  if (!updateStatusResponse.value || !updateStatusResponse.value.success) {
    showToast({ title: 'Не удалось изменить цену' })
  }
  emit('updateSeasonDaysData', false)
}
</script>

<template>
  <div class="form-labels form-inline">
    <div class="form-field field-daterange field-period field-required">
      <DateRangePicker
        :id="periodElementID"
        label="Период"
        required
        :min-date="seasonPeriod.from"
        :max-date="seasonPeriod.to"
        :value="seasonFormData.period"
        @input="(dates) => seasonFormData.period = dates as [Date, Date]"
      />
    </div>
    <div class="form-field field-select field-days field-required">
      <SelectComponent
        :options="daysOfWeekOptions"
        label="Выберите дни недели"
        :value="seasonFormData.daysWeekSelected"
        required
        multiple
        placeholder="Не выбрано"
        :minimize="true"
        @change="(value) => {
          seasonFormData.daysWeekSelected = value
        }"
      />
    </div>
    <div class="form-field field-price field-required">
      <label :for="priceElementID">Цена</label>
      <input :id="priceElementID" v-model="seasonFormData.price" required type="text" class="form-control">
    </div>
    <div class="form-field form-button">
      <BootstrapButton
        label="Обновить"
        size="small"
        severity="primary"
        :disabled="!isValidForm"
        @click="onSubmitUpdateData"
      />
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
    line-height: 1.313rem;
  }

  .form-field {
    margin-right: 1rem;
    margin-bottom: 1rem;

    label {
      margin-bottom: 0;
      font-size: 0.625rem;
    }

    input,
    .label {
      font-size: 0.688rem;
    }
  }

  .field-period {
    width: 11.25rem;

    input {
      text-align: right;
    }
  }

  .field-days {
    width: 16.25rem;
  }

  .field-price {
    width: 5.625rem;

    input {
      text-align: right;
    }
  }
}
</style>
