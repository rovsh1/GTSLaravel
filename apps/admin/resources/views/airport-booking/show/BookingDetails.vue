<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { MaybeRef } from '@vueuse/core'

import TouristModal from '~resources/views/airport-booking/show/components/TouristModal.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import InfoBlock from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlockTitle.vue'
import TouristsTable from '~resources/views/hotel-booking/show/components/TouristsTable.vue'

import { useCountrySearchAPI } from '~api/country'
import {
  HotelMarkupSettingsConditionAddProps,
  HotelMarkupSettingsUpdateProps,
} from '~api/hotel/markup-settings'

import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import BootstrapCardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import IconButton from '~components/IconButton.vue'

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const isEditableStatus = computed(() => true)

onMounted(() => {
  fetchCountries()
})

const modalSettings = {
  add: {
    title: 'Добавление туриста',
    handler: async (request: MaybeRef<HotelMarkupSettingsConditionAddProps>) => {
      console.log(request)
      // await addConditionHotelMarkupSettings(request)
    },
  },
  edit: {
    title: 'Изменение туриста',
    handler: async (request: MaybeRef<HotelMarkupSettingsUpdateProps>) => {
      console.log(request)
      // await updateConditionHotelMarkupSettings(request)
    },
  },
}

const {
  isOpened: isTouristModalOpened,
  isLoading: isTouristModalLoading,
  title: touristModalTitle,
  openAdd: openAddTouristModal,
  // openEdit: openEditTouristModal,
  // editableId: touristId,
  // editableObject: tourist,
  close: closeTouristModal,
  // submit: submitTouristModal,
} = useEditableModal(modalSettings)

</script>

<template>
  <TouristModal
    v-if="countries"
    :opened="isTouristModalOpened"
    :loading="isTouristModalLoading"
    :title="touristModalTitle"
    :countries="countries"
    @close="closeTouristModal"
  />

  <BootstrapCard>
    <BootstrapCardTitle class="mr-4" title="Информация о брони" />

    <div class="d-flex gap-4">
      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Туристы" />
            <IconButton
              v-if="isEditableStatus"
              icon="add"
              @click="openAddTouristModal"
            />
          </div>
        </template>

        <TouristsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :tourists="[]"
          :order-tourists="[]"
          :countries="countries"
        />
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <InfoBlockTitle title="Стоимость брони" />
        </template>
      </InfoBlock>
    </div>
  </BootstrapCard>
</template>
