<script setup lang="ts">

import { computed } from 'vue'

import { formatDate } from 'gts-common/helpers/date'
import CollapsableBlock from 'gts-components/Base/CollapsableBlock'

import CancelPeriodSettingsTable from '~resources/views/settings/components/CancelPeriodSettingsTable.vue'

import { CancelPeriod } from '~api/hotel/markup-settings'

import { useMarkupSettingsStore } from '~stores/markup-settings'

const markupSettingsStore = useMarkupSettingsStore()
const cancelPeriods = computed(() => markupSettingsStore.markupSettings?.cancelPeriods)

const isFetchingMarkupSettings = computed(() => markupSettingsStore.isFetching)

const getCancelConditionLabel = (cancelPeriod: CancelPeriod) => `Период с ${formatDate(cancelPeriod.from)} по ${formatDate(cancelPeriod.to)}`

</script>

<template>
  <CollapsableBlock id="cancellation-conditions" title="Условия отмены" class="card-grid">
    <div v-for="(cancelPeriod, idx) in cancelPeriods" :key="idx">
      <CancelPeriodSettingsTable
        :title="getCancelConditionLabel(cancelPeriod)"
        :cancel-period="cancelPeriod"
        :daily-markups="cancelPeriod.dailyMarkups"
        :loading="isFetchingMarkupSettings"
      />
    </div>
    <div v-if="!cancelPeriods?.length" class="grid-empty-text">Записи отсутствуют</div>
  </CollapsableBlock>
</template>
