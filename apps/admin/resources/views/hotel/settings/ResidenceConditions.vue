<script setup lang="ts">

import { ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import TimeSelect from '~resources/views/hotel/settings/components/TimeSelect.vue'

import { HotelMarkupSettingsUpdateProps,
  MarkupCondition,
  useHotelMarkupSettingsAPI,
  useHotelMarkupSettingsUpdateAPI } from '~api/hotel/markup-settings'

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

const editableConditionKey = ref<string>()
const editableCondition = ref<MarkupCondition>()
const updateMarkupSettingsPayload = ref<HotelMarkupSettingsUpdateProps | null>(null)

const {
  execute: fetchUpdateMarkupSettings,
  isFetching: isModalFetching,
} = useHotelMarkupSettingsUpdateAPI(updateMarkupSettingsPayload)

const [isShowModal, toggleModal] = useToggle()
const modalTitle = ref<string>('Добавить новое условие')

const handleEditConditions = (
  conditionType: ConditionType,
  condition: MarkupCondition,
  index: number,
) => {
  modalTitle.value = 'Изменение условия'
  editableCondition.value = condition
  editableConditionKey.value = `${conditionType}.${index}`

  toggleModal()
}

// eslint-disable-next-line unused-imports/no-unused-vars
const handleAddConditions = (conditionType: ConditionType) => {
  modalTitle.value = 'Добавить новое условие'

  toggleModal()
}

const conditionsModalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!conditionsModalForm.value?.reportValidity()) {
    return
  }

  updateMarkupSettingsPayload.value = {
    hotelID,
    key: editableConditionKey.value as string,
    value: editableCondition.value as MarkupCondition,
  }

  await fetchUpdateMarkupSettings()
  await fetchMarkupSettings()
  toggleModal(false)
}

fetchMarkupSettings()

</script>

<template>
  <BaseDialog
    :opened="isShowModal as boolean"
    :loading="isFetching || isModalFetching"
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

    <div v-if="!isFetching" class="d-flex flex-row gap-4 mt-4">
      <div class="w-100">
        <h6>Условия раннего заезда</h6>
        <table class="table">
          <tbody>
            <tr v-for="(condition, idx) in markupSettings?.earlyCheckIn" :key="`${condition.from}_${condition.to}`">
              <td class="column-edit">
                <EditTableRowButton
                  @click.prevent="handleEditConditions(ConditionTypeEnum.earlyCheckIn, condition, idx)"
                />
              </td>
              <td>С {{ condition.from }} до {{ condition.to }}</td>
              <td>{{ condition.percent }}%</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="w-100">
        <h6>Условия позднего выезда</h6>
        <table class="table">
          <tbody>
            <tr v-for="(condition, idx) in markupSettings?.lateCheckOut" :key="`${condition.from}_${condition.to}`">
              <td class="column-edit">
                <EditTableRowButton
                  @click.prevent="handleEditConditions(ConditionTypeEnum.lateCheckOut, condition, idx)"
                />
              </td>
              <td>С {{ condition.from }} до {{ condition.to }}</td>
              <td>{{ condition.percent }}%</td>
            </tr>
          </tbody>
        </table>
      </div>
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
