<script lang="ts" setup>
import { computed, reactive, watch } from 'vue'

import { nanoid } from 'nanoid'

import { daysOfWeekOptions } from '~resources/js/config/constants'

import { HotelRoom } from '~api/hotel/room'

import DateRangePicker from '~components/DateRangePicker.vue'
import OverlayLoading from '~components/OverlayLoading.vue'
import SelectComponent from '~components/SelectComponent.vue'

import { Action, ActionsOption, QuotasStatusUpdateFormData, QuotasStatusUpdatePayload } from './lib/types'

const props = withDefaults(defineProps<{
  rooms: HotelRoom[]
  disabled?: boolean
  initialPeriod?: [Date, Date] | undefined
  reInitForm?: boolean
}>(), {
  initialPeriod: undefined,
  reInitForm: false,
  disabled: false,
})

const emit = defineEmits<{
  (event: 'submit', filtersPayload: QuotasStatusUpdatePayload | null): void
}>()

const actionsOptions: ActionsOption[] = [
  { value: 'open', label: 'Открыть' },
  { value: 'close', label: 'Закрыть' },
]

const periodElementID = `${nanoid()}_period`

const updateQuotasFormData = reactive<QuotasStatusUpdateFormData>({
  period: props.initialPeriod,
  daysWeekSelected: <string[]>[],
  selectedRoomsID: <string[]>[],
  action: <Action | null>null,
})

const rooms = computed(() => props.rooms.map(({ id, name }) => ({
  value: id,
  label: name,
})))

const isValidForm = computed(() => updateQuotasFormData.period?.length === 2
&& !!updateQuotasFormData.daysWeekSelected.length
&& !!updateQuotasFormData.selectedRoomsID.length
&& !!updateQuotasFormData.action)

watch(isValidForm, (newValue) => {
  if (newValue) {
    emit('submit', {
      dateFrom: updateQuotasFormData.period?.[0] as Date,
      dateTo: updateQuotasFormData.period?.[1] as Date,
      daysWeekSelected: updateQuotasFormData.daysWeekSelected.map((str) => parseInt(str, 10)),
      selectedRoomsID: updateQuotasFormData.selectedRoomsID.map((str) => parseInt(str, 10)),
      action: updateQuotasFormData.action as Action,
    })
  } else {
    emit('submit', null)
  }
})

watch(() => props.reInitForm, (newValue) => {
  if (newValue) {
    updateQuotasFormData.period = props.initialPeriod
    updateQuotasFormData.daysWeekSelected = []
    updateQuotasFormData.selectedRoomsID = []
    updateQuotasFormData.action = null
  }
})
</script>

<template>
  <div class="form-labels">
    <OverlayLoading v-if="disabled" />
    <div class="form-field field-daterange field-required mb-4">
      <DateRangePicker
        :id="periodElementID"
        label="Период"
        :label-outline="false"
        required
        :disabled="disabled"
        :value="updateQuotasFormData.period"
        @input="(dates) => updateQuotasFormData.period = dates as [Date, Date]"
      />
    </div>
    <div class="form-field field-select field-required mb-4">
      <SelectComponent
        :options="daysOfWeekOptions"
        label="Выберите дни недели"
        label-style="outline"
        :value="updateQuotasFormData.daysWeekSelected"
        required
        multiple
        placeholder="Не выбрано"
        :disabled="disabled"
        @change="(value) => {
          updateQuotasFormData.daysWeekSelected = value
        }"
      />
    </div>
    <div class="form-field field-select field-required mb-4">
      <SelectComponent
        :options="rooms"
        label="Выберите номер(а)"
        label-style="outline"
        :value="updateQuotasFormData.selectedRoomsID"
        required
        multiple
        placeholder="Не выбрано"
        :disabled="disabled"
        @change="(value) => {
          updateQuotasFormData.selectedRoomsID = value
        }"
      />
    </div>
    <div class="form-field field-select field-required">
      <SelectComponent
        :options="actionsOptions"
        label="Действие"
        label-style="outline"
        required
        :disabled="disabled"
        :value="updateQuotasFormData.action"
        :allow-empty-item="true"
        empty-item="Не выбрано"
        :returned-empty-value="''"
        @change="(value) => {
          updateQuotasFormData.action = value.toString() as unknown as Action
        }"
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
}
</style>
