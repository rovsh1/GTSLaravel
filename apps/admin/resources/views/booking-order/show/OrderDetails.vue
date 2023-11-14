<script setup lang="ts">

import { computed, MaybeRef, onMounted, ref, unref, watch } from 'vue'

import GuestModal from '~resources/views/booking/shared/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/shared/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { GuestFormData } from '~resources/views/booking/shared/lib/data-types'
import BookingsTable from '~resources/views/booking-order/show/components/BookingsTable.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { addOrderGuest, updateOrderGuest } from '~api/booking/order/guest'
import { useCountrySearchAPI } from '~api/country'

import ActionsMenu, { Action } from '~components/ActionsMenu.vue'
import IconButton from '~components/IconButton.vue'

const orderStore = useOrderStore()

const isEditableStatus = computed<boolean>(() => orderStore.availableActions?.isEditable || false)

const orderId = computed(() => orderStore.order?.id)
const orderGuests = computed(() => orderStore.guests)
const orderGuestsIds = computed(() => orderStore.guests?.map((guest) => guest.id))

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const getDefaultGuestForm = () => ({ isAdult: true })

const actionsMenuSettings: Action[] = [{
  title: 'Создать отельную бронь',
  callback: () => {},
},
{
  title: 'Создать бронь услуги',
  callback: () => {},
}]

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      const preparedRequest = unref(request)
      let isSuccessesRequest = false
      const payload = { hotelBookingId: 0, hotelBookingRoomId: 0, ...preparedRequest }
      payload.orderId = orderId.value || 0
      const response = await addOrderGuest(payload)
      isSuccessesRequest = !!response.data.value?.id || false
      await orderStore.fetchOrder()
      // await orderStore.fetchGuests()
      return isSuccessesRequest
    },
  },
  edit: {
    title: 'Редактирование гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      payload.orderId = orderId.value || 0
      const response = await updateOrderGuest(payload)
      // await orderStore.fetchGuests()
      return response.data.value?.success || false
    },
  },
}

const {
  isOpened: isGuestModalOpened,
  isLoading: isGuestModalLoading,
  title: guestModalTitle,
  openAdd: openAddGuestModal,
  openEdit: openEditGuestModal,
  editableObject: editableGuest,
  close: closeGuestModal,
  submit: submitGuestModal,
} = useEditableModal<Required<GuestFormData>, Required<GuestFormData>, Partial<GuestFormData>>(modalSettings)

const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())

watch(editableGuest, (value) => {
  if (!value) {
    guestForm.value = getDefaultGuestForm()
    guestForm.value.selectedGuestFromOrder = undefined
    return
  }
  guestForm.value = value
})

onMounted(() => {
  fetchCountries()
})

</script>

<template>
  <GuestModal
    v-if="countries"
    :title-text="guestModalTitle"
    :opened="isGuestModalOpened"
    :is-fetching="isGuestModalLoading"
    :form-data="guestForm"
    :order-guests="orderGuests"
    :countries="countries"
    @close="closeGuestModal()"
    @submit="submitGuestModal"
    @clear="guestForm = getDefaultGuestForm()"
  />

  <div class="mt-3" />
  <div class="d-flex flex-row gap-4">
    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1 align-items-center">
          <InfoBlockTitle title="Список броней" />
          <ActionsMenu
            :can-edit="isEditableStatus"
            dropdown-button-icon="add"
            :actions="actionsMenuSettings"
          />
        </div>
      </template>

      <BookingsTable
        :can-edit="isEditableStatus"
        :order-bookings="[]"
        @edit="(booking) => {}"
        @delete="(booking) => {}"
      />
    </InfoBlock>
    <div class="w-100">
      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1 align-items-center">
            <InfoBlockTitle title="Список гостей" />
            <IconButton
              v-if="isEditableStatus"
              icon="add"
              @click="() => {
                openAddGuestModal()
              }"
            />
          </div>
        </template>

        <GuestsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :guest-ids="orderGuestsIds"
          :order-guests="orderGuests || []"
          :countries="countries"
          @edit="(guest) => {
            openEditGuestModal(guest.id, guest)
          }"
          @delete="(guest) => {}"
        />
      </InfoBlock>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.prices-information {
  .prices-information-details {
    font-weight: 400;
    font-style: italic;

    &>* {
      vertical-align: middle
    }

    .informationIcon {
      width: auto;
      height: 1.4em;
    }

    .prices-information-details-button {
      margin-left: 0.4rem;
      border: none;
      background-color: transparent;
      outline: none;
      cursor: pointer;
    }

    .prices-information-details-info {
      display: inline-block;

      .prices-information-details-info-icon {
        opacity: 0.5;
      }
    }

    .prices-information-details-button-icon,
    .prices-information-details-info-icon {
      font-size: 1.25rem;
    }
  }
}

.pt-card-title {
  margin-top: 0.85rem;
  padding-top: 0;
}
</style>
