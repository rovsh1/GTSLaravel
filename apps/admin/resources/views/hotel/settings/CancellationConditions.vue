<script setup lang="ts">

import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import CancelConditionsTable from '~resources/views/hotel/settings/components/CancelConditionsTable.vue'
import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import { useMarkupSettingsStore } from '~resources/views/hotel/settings/composables/markup-settings'

import CancellationConditions from './components/CancelConditionModal.vue'

const markupSettingsStore = useMarkupSettingsStore()
const cancelPeriods = computed(() => markupSettingsStore.markupSettings?.cancelPeriods)

const modalSettings = {
  add: {
    title: 'Добавить новое условие',
    handler: async (request: MaybeRef<any>) => {
      console.log('hanlde add')
    },
  },
  edit: {
    title: 'Изменение условия',
    handler: async (request: MaybeRef<any>) => {
      console.log('hanlde edit')
    },
  },
}

const { openAdd, isOpened, isLoading, close, title } = useEditableModal(modalSettings)

const addCancellationCondition = (): void => {
  openAdd()
}

</script>

<template>
  <CancellationConditions
    :opened="isOpened"
    :loading="isLoading"
    :title="title"
    @close="close"
  />

  <CollapsableBlock id="cancellation-conditions" title="Условия отмены">
    <template #header-controls>
      <button type="button" class="btn btn-add" @click="addCancellationCondition">
        <i class="icon">add</i>
        Добавить период
      </button>
    </template>

    <div v-for="(cancelPeriod, idx) in cancelPeriods" :key="idx">
      <CancelConditionsTable
        :title="`${cancelPeriod.from} ${cancelPeriod.to}`"
        :conditions="[]"
      />
    </div>
  </CollapsableBlock>
</template>
