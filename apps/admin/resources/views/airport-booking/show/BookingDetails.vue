<script setup lang="ts">

import { computed, onMounted, ref, unref, watch } from 'vue'

import { MaybeRef, useToggle } from '@vueuse/core'
import { z } from 'zod'

import GuestModal from '~resources/views/airport-booking/show/components/GuestModal.vue'
import { useBookingStore } from '~resources/views/airport-booking/show/store/booking'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import AmountBlock from '~resources/views/hotel-booking/show/components/AmountBlock.vue'
import GuestsTable from '~resources/views/hotel-booking/show/components/GuestsTable.vue'
import InfoBlock from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlockTitle.vue'
import PriceModal from '~resources/views/hotel-booking/show/components/PriceModal.vue'
import { GuestFormData } from '~resources/views/hotel-booking/show/lib/data-types'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order'

import { deleteBookingGuest } from '~api/booking/airport/guests'
import { updateBookingPrice } from '~api/booking/airport/price'
import { addOrderGuest, Guest, updateOrderGuest } from '~api/booking/order/guest'
import { useCountrySearchAPI } from '~api/country'
import { Currency } from '~api/models'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'
import { formatPrice } from '~lib/price'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import IconButton from '~components/IconButton.vue'

const { bookingID } = requestInitialData('view-initial-data-airport-booking', z.object({
  bookingID: z.number(),
  // manager: z.object({
  //   id: z.number(),
  // }),
}))

const bookingStore = useBookingStore()
const orderStore = useOrderStore()
const orderCurrency = computed<Currency | undefined>(() => orderStore.currency)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])
const booking = computed(() => bookingStore.booking)
const isEditableStatus = computed(() => true)
const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const [isHoPriceModalOpened, toggleHoPriceModal] = useToggle<boolean>(false)
const [isBoPriceModalOpened, toggleBoPriceModal] = useToggle<boolean>(false)
const [isHoPenaltyModalOpened, toggleHoPenaltyModal] = useToggle<boolean>(false)
const [isBoPenaltyModalOpened, toggleBoPenaltyModal] = useToggle<boolean>(false)

onMounted(() => {
  fetchCountries()
})

const modalSettings = {
  add: {
    title: 'Добавление туриста',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { airportBookingId: bookingID, ...preparedRequest }
      await addOrderGuest(payload)
      await orderStore.fetchGuests()
      await bookingStore.fetchBooking()
    },
  },
  edit: {
    title: 'Изменение туриста',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      await updateOrderGuest(payload)
      await orderStore.fetchGuests()
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

const getDefaultGuestForm = () => ({ isAdult: true })
const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())
watch(editableGuest, (value) => {
  if (!value) {
    guestForm.value = getDefaultGuestForm()
    return
  }
  guestForm.value = value
})

const handleDeleteGuest = async (guestId: number) => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить гостя?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingGuest({ bookingID, guestId })
    await bookingStore.fetchBooking()
    toggleClose()
  }
}

const handleSaveBoManualPrice = async (value: number | undefined) => {
  toggleBoPriceModal(false)
  await updateBookingPrice({
    bookingID,
    boPrice: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveHoManualPrice = async (value: number | undefined) => {
  toggleHoPriceModal(false)
  await updateBookingPrice({
    bookingID,
    hoPrice: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveBoPenalty = async (value: number | undefined) => {
  toggleBoPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    boPenalty: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveHoPenalty = async (value: number | undefined) => {
  toggleHoPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    hoPenalty: value,
  })
  bookingStore.fetchBooking()
}

</script>

<template>
  <GuestModal
    v-if="countries"
    :opened="isGuestModalOpened"
    :loading="isGuestModalLoading"
    :title="guestModalTitle"
    :countries="countries"
    :form-data="guestForm"
    @close="closeGuestModal"
    @submit="submitGuestModal"
  />

  <Card>
    <CardTitle class="mr-4" title="Информация о брони" />

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
          :guest-ids="booking?.guestIds"
          :order-guests="orderGuests"
          :countries="countries"
          @edit="guest => openEditGuestModal(guest.id, guest)"
          @delete="(guest) => handleDeleteGuest(guest.id)"
        />
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <InfoBlockTitle title="Стоимость брони" />
        </template>
        <PriceModal
          header="Общая сумма (нетто)"
          label="Общая сумма (нетто) в UZS"
          :value="booking?.price.hoPrice.isManual ? booking?.price.hoPrice.value : undefined"
          :opened="isHoPriceModalOpened"
          @close="toggleHoPriceModal(false)"
          @submit="handleSaveHoManualPrice"
        />

        <PriceModal
          header="Общая сумма (брутто)"
          :label="`Общая сумма (брутто) ${orderCurrency?.code_char}`"
          :value="booking?.price.boPrice.isManual ? booking?.price.boPrice.value : undefined"
          :opened="isBoPriceModalOpened"
          @close="toggleBoPriceModal(false)"
          @submit="handleSaveBoManualPrice"
        />

        <PriceModal
          header="Сумма штрафа для клиента"
          :label="`Сумма штрафа для клиента в ${orderCurrency?.code_char}`"
          :value="booking?.price.boPenalty || undefined"
          :opened="isBoPenaltyModalOpened"
          @close="toggleBoPenaltyModal(false)"
          @submit="handleSaveBoPenalty"
        />

        <PriceModal
          header="Сумма штрафа от гостиницы"
          :label="`Сумма штрафа от гостиницы в ${orderCurrency?.code_char}`"
          :value="booking?.price.hoPenalty || undefined"
          :opened="isHoPenaltyModalOpened"
          @close="toggleHoPenaltyModal(false)"
          @submit="handleSaveHoPenalty"
        />
        <div class="d-flex flex-row gap-3">
          <pre>
              {{ booking }}
            </pre>

          <AmountBlock
            v-if="booking"
            title="Приход"
            :currency="orderCurrency"
            amount-title="Общая сумма (брутто)"
            :amount-value="booking.price.boPrice.value"
            penalty-title="Сумма штрафа для клиента"
            :penalty-value="booking.price.boPenalty"
            :need-show-penalty="(booking?.price.hoPenalty || 0) > 0"
            @click-change-price="toggleBoPriceModal(true)"
            @click-change-penalty="toggleBoPenaltyModal(true)"
          />

          <AmountBlock
            v-if="booking"
            title="Расход"
            :currency="orderCurrency"
            amount-title="Общая сумма (нетто)"
            :amount-value="booking.price.hoPrice.value"
            penalty-title="Сумма штрафа от гостиницы"
            :penalty-value="booking.price.hoPenalty"
            :need-show-penalty="(booking?.price.hoPenalty || 0) > 0"
            @click-change-price="toggleHoPriceModal(true)"
            @click-change-penalty="toggleHoPenaltyModal(true)"
          />
        </div>

        <div v-if="booking && orderCurrency" class="mt-2">
          Прибыль = {{ formatPrice(booking.price.boPrice.value, orderCurrency.sign) }} -
          {{ formatPrice(booking.price.hoPrice.value, orderCurrency.sign) }} =
          {{ formatPrice((booking.price.boPrice.value - booking.price.hoPrice.value), orderCurrency.sign) }}
        </div>
      </InfoBlock>
    </div>
  </Card>
</template>
