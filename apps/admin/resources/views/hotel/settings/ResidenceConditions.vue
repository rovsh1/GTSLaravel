<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import ConditionsTable from '~resources/views/hotel/settings/components/ConditionsTable.vue'
import TimeSelect from '~resources/views/hotel/settings/components/TimeSelect.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import { ConditionType, ConditionTypeEnum, useMarkupSettingsStore } from '~resources/views/hotel/settings/composables/markup-settings'
import { useTimeSettings } from '~resources/views/hotel/settings/composables/time-settings'

import {
  addConditionHotelMarkupSettings,
  deleteConditionHotelMarkupSettings,
  HotelMarkupSettingsConditionAddProps,
  HotelMarkupSettingsUpdateProps,
  MarkupCondition,
  updateConditionHotelMarkupSettings,
} from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'

const { hotelID } = requestInitialData(
  'view-initial-data-hotel-settings',
  z.object({
    hotelID: z.number(),
  }),
)

const markupSettingsStore = useMarkupSettingsStore()
const { fetchMarkupSettings } = markupSettingsStore
const isFetching = computed(() => markupSettingsStore.isFetching)
const markupSettings = computed(() => markupSettingsStore.markupSettings)
const {
  checkOutBefore,
  checkInAfter,
  breakfastPeriodFrom,
  breakfastPeriodTo,
  fetchTimeSettings,
  updateTimeSettings,
} = useTimeSettings(hotelID)

type AddPayload = HotelMarkupSettingsConditionAddProps
type EditPayload = HotelMarkupSettingsUpdateProps

const modalSettings = {
  add: {
    title: 'Добавить новое условие',
    handler: async (request: MaybeRef<AddPayload>) => {
      await addConditionHotelMarkupSettings(request)
    },
  },
  edit: {
    title: 'Изменение условия',
    handler: async (request: MaybeRef<EditPayload>) => {
      await updateConditionHotelMarkupSettings(request)
    },
  },
}

const {
  isOpened,
  isLoading,
  title: modalTitle,
  submit: submitModal,
  close,
  openAdd,
  openEdit,
} = useEditableModal<AddPayload, EditPayload, MarkupCondition>(modalSettings)

const isConditionsFetching = ref<boolean>(false)
const editableConditionKey = ref<string>()
const editableCondition = ref<MarkupCondition>()

const handleEditConditions = (conditionType: ConditionType, condition: MarkupCondition, index: number) => {
  editableCondition.value = condition
  editableConditionKey.value = `${conditionType}.${index}`
  openEdit(index, condition)
}

const handleAddConditions = (conditionType: ConditionType) => {
  editableCondition.value = { from: '', to: '' } as unknown as MarkupCondition
  editableConditionKey.value = `${conditionType}`
  openAdd()
}

const handleDeleteConditions = async (conditionType: ConditionType, index: number) => {
  isConditionsFetching.value = true
  await deleteConditionHotelMarkupSettings({
    hotelID,
    key: conditionType,
    index,
  })
  isConditionsFetching.value = false
  await fetchMarkupSettings()
}

const conditionsModalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!conditionsModalForm.value?.reportValidity()) {
    return
  }

  const payload = {
    hotelID,
    key: editableConditionKey.value as string,
    value: editableCondition.value as MarkupCondition,
  }

  isConditionsFetching.value = true
  await submitModal(payload)
  isConditionsFetching.value = false
  await fetchMarkupSettings()
}

const handleUpdateHotelSettings = async () => {
  await updateTimeSettings()
  await fetchTimeSettings()
}

</script>

<template>
  <BaseDialog
    :opened="isOpened as boolean"
    :loading="isLoading || isConditionsFetching"
    @close="close"
  >
    <template #title>{{ modalTitle }}</template>

    <form v-if="editableCondition" ref="conditionsModalForm" class="row g-3" @submit.prevent="onModalSubmit">
      <div class="col-md-6">
        <TimeSelect id="from" v-model="editableCondition.from" label="Начало" required />
      </div>
      <div class="col-md-6">
        <TimeSelect id="to" v-model="editableCondition.to" label="Конец" required />
      </div>
      <div class="col-md-12 field-required">
        <label for="markup">Наценка</label>
        <input
          id="markup"
          v-model="editableCondition.percent"
          type="number"
          class="form-control"
          required
        >
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="close">Отмена</button>
    </template>
  </BaseDialog>

  <CollapsableBlock id="residence-conditions" title="Порядок проживания">
    <div class="d-flex flex-row gap-4">
      <div class="w-100">
        <h6>Стандартные условия заезда</h6>
        <TimeSelect
          v-model="checkInAfter"
          class="w-25"
          @change="handleUpdateHotelSettings"
        />
      </div>
      <div class="w-100">
        <h6>Стандартные условия выезда</h6>
        <TimeSelect
          v-model="checkOutBefore"
          class="w-25"
          @change="handleUpdateHotelSettings"
        />
      </div>
    </div>

    <div class="d-flex flex-row gap-4 mt-4">
      <ConditionsTable
        class="w-100"
        title="Условия раннего заезда"
        :conditions="markupSettings?.earlyCheckIn || [] as MarkupCondition[]"
        :loading="isFetching || isConditionsFetching"
        @add="handleAddConditions(ConditionTypeEnum.earlyCheckIn)"
        @edit="({ condition, index }) => handleEditConditions(ConditionTypeEnum.earlyCheckIn, condition, index)"
        @delete="index => handleDeleteConditions(ConditionTypeEnum.earlyCheckIn, index)"
      />
      <ConditionsTable
        class="w-100"
        title="Условия позднего выезда"
        :conditions="markupSettings?.lateCheckOut || [] as MarkupCondition[]"
        :loading="isFetching || isConditionsFetching"
        @add="handleAddConditions(ConditionTypeEnum.lateCheckOut)"
        @edit="({ condition, index }) => handleEditConditions(ConditionTypeEnum.lateCheckOut, condition, index)"
        @delete="index => handleDeleteConditions(ConditionTypeEnum.lateCheckOut, index)"
      />
    </div>

    <div class="d-flex flex-row gap-4 mt-2">
      <div class="w-100">
        <h6>Время завтрака</h6>
        <div class="d-flex flex-row gap-2">
          <TimeSelect
            v-model="breakfastPeriodFrom"
            label="C"
            class="w-25"
            @change="handleUpdateHotelSettings"
          />
          <TimeSelect
            v-model="breakfastPeriodTo"
            :from="breakfastPeriodFrom"
            label="До"
            class="w-25"
            @change="handleUpdateHotelSettings"
          />
        </div>
      </div>
      <div class="w-100" />
    </div>
  </CollapsableBlock>
</template>
