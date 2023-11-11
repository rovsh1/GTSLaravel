<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import DailyMarkupModal from '~resources/views/hotel/settings/components/DailyMarkupModal.vue'

import {
  addConditionHotelMarkupSettings,
  CancelPeriod, DailyMarkup,
  deleteConditionHotelMarkupSettings,
  HotelMarkupSettingsConditionAddProps,
  HotelMarkupSettingsUpdateProps,
  updateConditionHotelMarkupSettings,
} from '~api/hotel/markup-settings'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { formatDate } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'

import CancelPeriodModal from './components/CancelPeriodModal.vue'
import CancelPeriodSettingsTable from './components/CancelPeriodSettingsTable.vue'
import CollapsableBlock from './components/CollapsableBlock.vue'

import { useEditableModal } from './composables/editable-modal'
import { useMarkupSettingsStore } from './composables/markup-settings'

const { hotelID, contract } = requestInitialData(
  'view-initial-data-hotel-settings',
  z.object({
    hotelID: z.number(),
    contract: z.object({
      id: z.number(),
      status: z.number(),
      date_start: z.string(),
      date_end: z.string(),
    }).nullable(),
  }),
)

const defaultDisabledDate = '1900-01-01'
const markupSettingsStore = useMarkupSettingsStore()
const cancelPeriods = computed(() => markupSettingsStore.markupSettings?.cancelPeriods)
const { fetchMarkupSettings, updateCancelPeriodDailyMarkupField, deleteCancelPeriodDailyMarkup } = markupSettingsStore

const isFetchingMarkupSettings = computed(() => markupSettingsStore.isFetching)

const modalSettings = {
  add: {
    title: 'Добавить новое условие',
    handler: async (request: MaybeRef<HotelMarkupSettingsConditionAddProps>): Promise<boolean> => {
      const response = await addConditionHotelMarkupSettings(request)
      return response.data.value?.success || false
    },
  },
  edit: {
    title: 'Изменение условия',
    handler: async (request: MaybeRef<HotelMarkupSettingsUpdateProps>): Promise<boolean> => {
      const response = await updateConditionHotelMarkupSettings(request)
      return response.data.value?.success || false
    },
  },
}

const {
  isOpened,
  isLoading,
  title,
  editableId,
  editableObject,
  openAdd,
  openEdit,
  close,
  submit,
} = useEditableModal<HotelMarkupSettingsConditionAddProps, HotelMarkupSettingsUpdateProps, CancelPeriod>(modalSettings)

const onModalSubmit = async (cancelPeriod: CancelPeriod): Promise<void> => {
  const key = editableId.value !== undefined ? `cancelPeriods.${editableId.value}` : 'cancelPeriods'
  const payload = { hotelID, key, value: cancelPeriod }
  await submit(payload)
  await fetchMarkupSettings()
}

const getCancelConditionLabel = (cancelPeriod: CancelPeriod) => `Период с ${formatDate(cancelPeriod.from)} по ${formatDate(cancelPeriod.to)}`

const handleDeletePeriod = async (index: number): Promise<void> => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить период?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteConditionHotelMarkupSettings({ hotelID, key: 'cancelPeriods', index })
    await fetchMarkupSettings()
    toggleClose()
  }
}

const editablePeriodId = ref<number>()

const dailyMarkupsModalSettings = {
  add: {
    title: 'Добавить новое условие',
    handler: async (request: MaybeRef<HotelMarkupSettingsConditionAddProps>): Promise<boolean> => {
      const response = await addConditionHotelMarkupSettings(request)
      return response.data.value?.success || false
    },
  },
  edit: {
    title: 'Изменить условие',
    handler: async (request: MaybeRef<HotelMarkupSettingsUpdateProps>): Promise<boolean> => {
      const response = await updateConditionHotelMarkupSettings(request)
      return response.data.value?.success || false
    },
  },
}

const {
  isOpened: isOpenedDailyModal,
  isLoading: isDailyModalLoading,
  title: dailyModalTitle,
  openAdd: openAddDailyModal,
  openEdit: openEditDailyModal,
  editableId: editableDailyMarkupId,
  editableObject: editableDailyMarkup,
  close: closeDailyModal,
  submit: submitDailyModal,
} = useEditableModal<HotelMarkupSettingsConditionAddProps, HotelMarkupSettingsUpdateProps, DailyMarkup>(dailyMarkupsModalSettings)

const handleAddDailyModal = (periodIndex: number) => {
  editablePeriodId.value = periodIndex
  openAddDailyModal()
}

const handleEditDailyModal = (periodIndex: number, dailyMarkupIndex: number) => {
  if (!cancelPeriods.value) {
    return
  }
  editablePeriodId.value = periodIndex
  openEditDailyModal(dailyMarkupIndex, cancelPeriods.value[periodIndex].dailyMarkups[dailyMarkupIndex])
}

const onSubmitDailyModal = async (dailyMarkup: DailyMarkup) => {
  let key = `cancelPeriods.${editablePeriodId.value}.dailyMarkups`
  if (editableDailyMarkupId.value !== undefined) {
    key += `.${editableDailyMarkupId.value}`
  }
  const payload = { hotelID, key, value: dailyMarkup }
  await submitDailyModal(payload)
  await fetchMarkupSettings()
}

const handleUpdateDailyMarkupField = (
  cancelPeriodIndex: number,
  dailyMarkupIndex: number,
  field: string,
  value: number,
) => updateCancelPeriodDailyMarkupField({ cancelPeriodIndex, dailyMarkupIndex, field, value })

const handleDeleteDailyMarkup = async (cancelPeriodIndex: number, dailyMarkupIndex: number) => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить период?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteCancelPeriodDailyMarkup({ cancelPeriodIndex, dailyMarkupIndex })
    await fetchMarkupSettings()
    toggleClose()
  }
}

</script>

<template>
  <CancelPeriodModal
    :value="editableObject"
    :opened="isOpened"
    :loading="isLoading"
    :title="title"
    :cancel-periods="contract ? cancelPeriods : undefined"
    :editable-id="editableId"
    :min-date="contract?.date_start || defaultDisabledDate"
    :max-date="contract?.date_end || defaultDisabledDate"
    @close="close"
    @submit="onModalSubmit"
  />

  <DailyMarkupModal
    :value="editableDailyMarkup"
    :opened="isOpenedDailyModal"
    :loading="isDailyModalLoading"
    :title="dailyModalTitle"
    @close="closeDailyModal"
    @submit="onSubmitDailyModal"
  />

  <CollapsableBlock id="cancellation-conditions" title="Условия отмены" class="card-grid">
    <template #header-controls>
      <button type="button" class="btn btn-add" @click="openAdd">
        <i class="icon">add</i>
        Добавить период
      </button>
    </template>

    <div v-for="(cancelPeriod, idx) in cancelPeriods" :key="idx">
      <CancelPeriodSettingsTable
        :title="getCancelConditionLabel(cancelPeriod)"
        :cancel-period="cancelPeriod"
        :daily-markups="cancelPeriod.dailyMarkups"
        :can-edit-base="Boolean(contract)"
        :loading="isFetchingMarkupSettings"
        @add="handleAddDailyModal(idx)"
        @edit-field="({ index, field, value }) => handleUpdateDailyMarkupField(idx, index, field, value)"
        @edit="index => handleEditDailyModal(idx, index)"
        @delete="index => handleDeleteDailyMarkup(idx, index)"
        @edit-base="openEdit(idx, cancelPeriod)"
        @delete-base="handleDeletePeriod(idx)"
      />
    </div>
    <div v-if="!cancelPeriods?.length" class="grid-empty-text">Записи отсутствуют</div>
  </CollapsableBlock>
</template>
