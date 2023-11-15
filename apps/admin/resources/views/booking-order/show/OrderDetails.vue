<script setup lang="ts">

import { computed, MaybeRef, onMounted, ref, unref, watch } from 'vue'

import { z } from 'zod'

import GuestModal from '~resources/views/booking/shared/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/shared/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { GuestFormData } from '~resources/views/booking/shared/lib/data-types'
import BookingsTable from '~resources/views/booking-order/show/components/BookingsTable.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { useCountrySearchAPI } from '~api/country'
import { addOrderGuest, updateOrderGuest } from '~api/order/guest'

import { requestInitialData } from '~lib/initial-data'

import ActionsMenu, { Action } from '~components/ActionsMenu.vue'
import IconButton from '~components/IconButton.vue'

const { serviceBookingCreate, hotelBookingCreate } = requestInitialData('view-initial-data-booking-order', z.object({
  serviceBookingCreate: z.string(),
  hotelBookingCreate: z.string(),
}))

const orderStore = useOrderStore()

const isEditableStatus = computed<boolean>(() => orderStore.availableActions?.isEditable || false)

const orderId = computed(() => orderStore.order?.id)
const orderGuests = computed(() => orderStore.guests)
const orderGuestsIds = computed(() => orderStore.guests?.map((guest) => guest.id))

const orderBookings = computed(() => orderStore.bookings)

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const getDefaultGuestForm = () => ({ isAdult: true })

const actionsMenuSettings: Action[] = [{
  title: 'Создать отельную бронь',
  callback: () => { location.href = hotelBookingCreate },
},
{
  title: 'Создать бронь услуги',
  callback: () => { location.href = serviceBookingCreate },
}]

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      if (!orderId.value) return false
      const preparedRequest = unref(request)
      let isSuccessesRequest = false
      const payload = { ...preparedRequest }
      payload.orderId = orderId.value
      const response = await addOrderGuest(payload)
      isSuccessesRequest = !!response.data.value?.id || false
      await orderStore.fetchOrder()
      await orderStore.fetchGuests()
      return isSuccessesRequest
    },
  },
  edit: {
    title: 'Редактирование гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      if (!orderId.value) return false
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      payload.orderId = orderId.value
      const response = await updateOrderGuest(payload)
      await orderStore.fetchOrder()
      await orderStore.fetchGuests()
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
        :order-bookings="orderBookings || []"
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
          :can-delete-guest="false"
          :guest-ids="orderGuestsIds"
          :order-guests="orderGuests || []"
          :countries="countries"
          @edit="(guest) => {
            openEditGuestModal(guest.id, guest)
          }"
        />
      </InfoBlock>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.pt-card-title {
  margin-top: 0.85rem;
  padding-top: 0;
}
</style>
