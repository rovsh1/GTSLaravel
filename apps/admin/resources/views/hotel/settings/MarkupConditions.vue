<script setup lang="ts">

import { computed } from 'vue'

import { z } from 'zod'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'
import { useMarkupSettingsStore } from '~resources/views/hotel/settings/composables/markup-settings'

import { updateConditionHotelMarkupSettings } from '~api/hotel/markup-settings'

import { requestInitialData } from '~lib/initial-data'

import EditableCell from '~components/Editable/EditableNumberInput.vue'

const { hotelID } = requestInitialData(
  'view-initial-data-hotel-settings',
  z.object({
    hotelID: z.number(),
  }),
)

const markupSettingsStore = useMarkupSettingsStore()
const { fetchMarkupSettings } = markupSettingsStore
const markupSettings = computed(() => markupSettingsStore.markupSettings)
const isFetching = computed(() => markupSettingsStore.isFetching)

const handleUpdateMarkupSettings = async (key: string, value: number | string): Promise<void> => {
  await updateConditionHotelMarkupSettings({ hotelID, key, value: Number(value) })
  fetchMarkupSettings()
}

</script>

<template>
  <CollapsableBlock id="price-conditions" title="Условия наценки" class="card-grid">
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Тур сбор</th>
          <th scope="col">НДС</th>
          <th scope="col">Физическое лицо</th>
          <th scope="col">OTA</th>
          <th scope="col">TA</th>
          <th scope="col">TO</th>
        </tr>
      </thead>
      <tbody :class="{ loading: isFetching }">
        <tr v-if="markupSettings">
          <td>
            <EditableCell
              :value="markupSettings.touristTax"
              dimension="%"
              placeholder="Тур сбор"
              @change="value => handleUpdateMarkupSettings('touristTax', value)"
            />
          </td>
          <td>
            <EditableCell
              :value="markupSettings.vat"
              dimension="%"
              placeholder="НДС"
              @change="value => handleUpdateMarkupSettings('vat', value)"
            />
          </td>
          <td>
            <EditableCell
              :value="markupSettings.clientMarkups.individual"
              dimension="%"
              placeholder="Физическое лицо"
              @change="value => handleUpdateMarkupSettings('clientMarkups.individual', value)"
            />
          </td>
          <td>
            <EditableCell
              :value="markupSettings.clientMarkups.OTA"
              dimension="%"
              placeholder="OTA"
              @change="value => handleUpdateMarkupSettings('clientMarkups.OTA', value)"
            />
          </td>
          <td>
            <EditableCell
              :value="markupSettings.clientMarkups.TA"
              dimension="%"
              placeholder="TA"
              @change="value => handleUpdateMarkupSettings('clientMarkups.TA', value)"
            />
          </td>
          <td>
            <EditableCell
              :value="markupSettings.clientMarkups.TO"
              dimension="%"
              placeholder="TO"
              @change="value => handleUpdateMarkupSettings('clientMarkups.TO', value)"
            />
          </td>
        </tr>
      </tbody>
    </table>
  </CollapsableBlock>
</template>
