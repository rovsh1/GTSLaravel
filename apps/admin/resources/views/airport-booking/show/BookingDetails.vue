<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { MaybeRef } from '@vueuse/core'

import GuestModal from '~resources/views/airport-booking/show/components/GuestModal.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import GuestsTable from '~resources/views/hotel-booking/show/components/GuestsTable.vue'
import InfoBlock from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlockTitle.vue'

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
  isOpened: isGuestModalOpened,
  isLoading: isGuestModalLoading,
  title: guestModalTitle,
  openAdd: openAddGuestModal,
  openEdit: openEditGuestModal,
  // editableId: touristId,
  // editableObject: tourist,
  close: closeGuestModal,
  // submit: submitTouristModal,
} = useEditableModal(modalSettings)

</script>

<template>
  <GuestModal
    v-if="countries"
    :opened="isGuestModalOpened"
    :loading="isGuestModalLoading"
    :title="guestModalTitle"
    :countries="countries"
    @close="closeGuestModal"
  />

  <BootstrapCard>
    <BootstrapCardTitle class="mr-4" title="Информация о брони" />

    <div class="d-flex gap-4">
      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Гости" />
            <IconButton
              v-if="isEditableStatus"
              icon="add"
              @click="openAddGuestModal"
            />
          </div>
        </template>

        <GuestsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :guests="[]"
          :order-guests="[]"
          :countries="countries"
          @edit="guest => openEditGuestModal(guest.id, guest)"
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
