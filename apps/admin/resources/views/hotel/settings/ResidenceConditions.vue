<script setup lang="ts">

import { ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import ConditionsTable from '~resources/views/hotel/settings/components/ConditionsTable.vue'
import TimeSelect from '~resources/views/hotel/settings/components/TimeSelect.vue'

import {
  addConditionHotelMarkupSettings,
  deleteConditionHotelMarkupSettings,
  MarkupCondition,
  updateConditionHotelMarkupSettings,
  useHotelMarkupSettingsAPI,
} from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'

enum ConditionTypeEnum {
  earlyCheckIn = 'earlyCheckIn',
  lateCheckOut = 'lateCheckOut',
}

type ConditionType = keyof typeof ConditionTypeEnum

const { hotelID } = requestInitialData(
  'view-initial-data-hotel-settings',
  z.object({
    hotelID: z.number(),
  }),
)

const {
  isFetching,
  execute: fetchMarkupSettings,
  data: markupSettings,
} = useHotelMarkupSettingsAPI({ hotelID })

const isConditionsFetching = ref<boolean>(false)

const editableConditionKey = ref<string>()
const editableCondition = ref<MarkupCondition>()

const [isShowModal, toggleModal] = useToggle()
const modalTitle = ref<string>('Добавить новое условие')
const isCreateCondition = ref<boolean>(false)

const handleEditConditions = (
  conditionType: ConditionType,
  condition: MarkupCondition,
  index: number,
) => {
  modalTitle.value = 'Изменение условия'
  editableCondition.value = condition
  editableConditionKey.value = `${conditionType}.${index}`
  isCreateCondition.value = false

  toggleModal()
}

const handleAddConditions = (conditionType: ConditionType) => {
  modalTitle.value = 'Добавить новое условие'
  editableCondition.value = { from: '', to: '' } as unknown as MarkupCondition
  editableConditionKey.value = `${conditionType}`
  isCreateCondition.value = true

  toggleModal()
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
  if (!isCreateCondition.value) {
    await updateConditionHotelMarkupSettings(payload)
  } else {
    await addConditionHotelMarkupSettings(payload)
  }
  isConditionsFetching.value = false

  await fetchMarkupSettings()
  toggleModal(false)
}

fetchMarkupSettings()

</script>

<template>
  <BaseDialog
    :opened="isShowModal as boolean"
    :loading="isFetching || isConditionsFetching"
    @close="toggleModal(false)"
  >
    <template #title>{{ modalTitle }}</template>

    <form v-if="editableCondition" ref="conditionsModalForm" class="row g-3">
      <div class="col-md-6">
        <TimeSelect id="from" v-model="editableCondition.from" label="Начало" />
      </div>
      <div class="col-md-6">
        <TimeSelect id="to" v-model="editableCondition.to" label="Конец" />
      </div>
      <div class="col-md-12">
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
      <button class="btn btn-cancel" type="button" @click="toggleModal(false)">Отмена</button>
    </template>
  </BaseDialog>

  <CollapsableBlock id="residence-conditions" title="Порядок проживания">
    <div class="d-flex flex-row gap-4">
      <div class="w-100">
        <h6>Стандартные условия заезда</h6>
        <TimeSelect class="w-25" />
      </div>
      <div class="w-100">
        <h6>Стандартные условия выезда</h6>
        <TimeSelect class="w-25" />
      </div>
    </div>

    <div class="d-flex flex-row gap-4 mt-4">
      <ConditionsTable
        class="w-100"
        title="Условия раннего заезда"
        :conditions="markupSettings?.earlyCheckIn as MarkupCondition[]"
        :loading="isFetching || isConditionsFetching"
        @add="handleAddConditions(ConditionTypeEnum.earlyCheckIn)"
        @edit="({ condition, index }) => handleEditConditions(ConditionTypeEnum.earlyCheckIn, condition, index)"
        @delete="index => handleDeleteConditions(ConditionTypeEnum.earlyCheckIn, index)"
      />
      <ConditionsTable
        class="w-100"
        title="Условия позднего выезда"
        :conditions="markupSettings?.lateCheckOut as MarkupCondition[]"
        :loading="isFetching || isConditionsFetching"
        @add="handleAddConditions(ConditionTypeEnum.lateCheckOut)"
        @edit="({ condition, index }) => handleEditConditions(ConditionTypeEnum.lateCheckOut, condition, index)"
        @delete="index => handleDeleteConditions(ConditionTypeEnum.lateCheckOut, index)"
      />
    </div>

    <div class="d-flex flex-row gap-4 mt-2">
      <div class="w-100">
        <h6>Время завтрака</h6>
        <TimeSelect class="w-25" />
      </div>
      <div class="w-100" />
    </div>
  </CollapsableBlock>
</template>
