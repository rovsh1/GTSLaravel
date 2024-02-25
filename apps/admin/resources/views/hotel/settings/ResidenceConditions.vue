<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import ConditionsTable from '~resources/views/hotel/settings/components/ConditionsTable.vue'
import MarkupConditionModal from '~resources/views/hotel/settings/components/MarkupConditionModal.vue'
import TimeSelect from '~resources/views/hotel/settings/components/TimeSelect.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import { ConditionType, ConditionTypeEnum, useMarkupSettingsStore } from '~resources/views/hotel/settings/composables/markup-settings'
import { useTimeConditions } from '~resources/views/hotel/settings/composables/time-conditions'
import { useTimeSettings } from '~resources/views/hotel/settings/composables/time-settings'

import {
  addConditionHotelMarkupSettings,
  deleteConditionHotelMarkupSettings,
  HotelMarkupSettingsConditionAddProps,
  HotelMarkupSettingsUpdateProps,
  MarkupCondition, Time,
  updateConditionHotelMarkupSettings,
} from '~api/hotel/markup-settings'

import { showToast } from '~components/Bootstrap/BootstrapToast'

import { requestInitialData } from 'gts-common/helpers/initial-data'

const { hotelID } = requestInitialData(
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
    handler: async (request: MaybeRef<AddPayload>): Promise<boolean> => {
      const response = await addConditionHotelMarkupSettings(request)
      return response.data.value?.success || false
    },
  },
  edit: {
    title: 'Изменение условия',
    handler: async (request: MaybeRef<EditPayload>): Promise<boolean> => {
      const response = await updateConditionHotelMarkupSettings(request)
      return response.data.value?.success || false
    },
  },
}

const {
  isOpened,
  isLoading,
  editableId,
  editableObject: editableCondition,
  title: modalTitle,
  submit: submitModal,
  close,
  openAdd,
  openEdit,
} = useEditableModal<AddPayload, EditPayload, MarkupCondition>(modalSettings)

const isConditionsFetching = ref<boolean>(false)
const editableConditionKey = ref<string>()
const { minTime, maxTime, setConditions, setLimits, freePeriods } = useTimeConditions()

const setConditionTimeLimits = (conditionType: ConditionType): void => {
  if (conditionType === 'earlyCheckIn') {
    setConditions(markupSettings.value?.earlyCheckIn || [])
    setLimits('00:00', checkInAfter.value as Time)
    return
  }

  setConditions(markupSettings.value?.lateCheckOut || [])
  setLimits(checkOutBefore.value as Time, '24:00')
}

const handleEditConditions = (conditionType: ConditionType, condition: MarkupCondition, index: number) => {
  editableCondition.value = condition
  editableConditionKey.value = `${conditionType}.${index}`
  setConditionTimeLimits(conditionType)
  openEdit(index, condition)
}

const handleAddConditions = (conditionType: ConditionType) => {
  editableCondition.value = { from: '', to: '' } as unknown as MarkupCondition
  editableConditionKey.value = `${conditionType}`
  setConditionTimeLimits(conditionType)
  if (freePeriods.value.length === 0) {
    showToast({ title: 'Все интервалы заняты' }, {
      type: 'warning',
    })
    return
  }
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

const onModalSubmit = async (markupCondition: MarkupCondition) => {
  const payload = {
    hotelID,
    key: editableConditionKey.value as string,
    value: markupCondition,
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
  <MarkupConditionModal
    v-if="checkInAfter && checkOutBefore"
    :value="editableCondition"
    :title="modalTitle"
    :opened="isOpened"
    :loading="isLoading || isConditionsFetching"
    :min="minTime"
    :max="maxTime"
    :free-periods="freePeriods"
    :is-edit-mode="editableId !== undefined"
    @close="close"
    @submit="onModalSubmit"
  />

  <CollapsableBlock id="residence-conditions" title="Порядок проживания">
    <div class="d-flex flex-row gap-4">
      <div class="w-100">
        <h6>Стандартные условия заезда</h6>
        <div class="row g-3 align-items-center">
          <label for="check-in-select" class="col-auto">С</label>
          <TimeSelect
            id="check-in-select"
            v-model="checkInAfter"
            class="col-auto w-25"
            @change="handleUpdateHotelSettings"
          />
        </div>
      </div>
      <div class="w-100">
        <h6>Стандартные условия выезда</h6>
        <div class="row g-3 align-items-center">
          <label for="check-out-select" class="col-auto">До</label>
          <TimeSelect
            id="check-out-select"
            v-model="checkOutBefore"
            class="col-auto w-25"
            @change="handleUpdateHotelSettings"
          />
        </div>
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
        <div class="row g-3 align-items-center">
          <label for="breakfast-from-select" class="col-auto">С</label>
          <TimeSelect
            id="breakfast-from-select"
            v-model="breakfastPeriodFrom"
            class="col-auto w-25"
            @change="handleUpdateHotelSettings"
          />
          <label for="breakfast-to-select" class="col-auto">До</label>
          <TimeSelect
            id="breakfast-to-select"
            v-model="breakfastPeriodTo"
            :min="breakfastPeriodFrom"
            class="col-auto w-25"
            @change="handleUpdateHotelSettings"
          />
        </div>
      </div>
      <div class="w-100" />
    </div>
  </CollapsableBlock>
</template>
