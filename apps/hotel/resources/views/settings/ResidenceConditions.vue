<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { useMarkupSettingsStore } from '~resources/store/markup-settings'
import ConditionsTable from '~resources/views/settings/components/ConditionsTable.vue'

import { MarkupCondition } from '~api/hotel/markup-settings'
import { useHotelSettingsAPI } from '~api/hotel/settings'

import CollapsableBlock from '~components/CollapsableBlock.vue'

const { data: hotelSettings, execute: fetchTimeSettings } = useHotelSettingsAPI()

const emptyValueTitle = '(Не установлено)'
const markupSettingsStore = useMarkupSettingsStore()
const isFetching = computed(() => markupSettingsStore.isFetching)
const markupSettings = computed(() => markupSettingsStore.markupSettings)

onMounted(fetchTimeSettings)

</script>

<template>
  <CollapsableBlock id="residence-conditions" title="Порядок проживания">
    <div class="d-flex flex-row gap-4">
      <div class="w-100">
        <h6>Стандартные условия заезда</h6>
        <div class="row g-3 align-items-center">
          <span class="col-auto">С</span>
          <span class="col-auto">{{ hotelSettings?.timeSettings.checkInAfter || emptyValueTitle }}</span>
        </div>
      </div>
      <div class="w-100">
        <h6>Стандартные условия выезда</h6>
        <div class="row g-3 align-items-center">
          <span class="col-auto">До</span>
          <span class="col-auto">{{ hotelSettings?.timeSettings.checkOutBefore || emptyValueTitle }}</span>
        </div>
      </div>
    </div>

    <div class="d-flex flex-row gap-4 mt-4">
      <ConditionsTable
        class="w-100"
        title="Условия раннего заезда"
        :conditions="markupSettings?.earlyCheckIn || [] as MarkupCondition[]"
        :loading="isFetching"
      />
      <ConditionsTable
        class="w-100"
        title="Условия позднего выезда"
        :conditions="markupSettings?.lateCheckOut || [] as MarkupCondition[]"
        :loading="isFetching"
      />
    </div>

    <div class="d-flex flex-row gap-4 mt-2">
      <div class="w-100">
        <h6>Время завтрака</h6>
        <div class="row g-3 align-items-center">
          <span class="col-auto">С</span>
          <span class="col-auto">{{ hotelSettings?.timeSettings.breakfastFrom || emptyValueTitle }}</span>
          <span class="col-auto">До</span>
          <span class="col-auto">{{ hotelSettings?.timeSettings.breakfastTo || emptyValueTitle }}</span>
        </div>
      </div>
      <div class="w-100" />
    </div>
  </CollapsableBlock>
</template>
