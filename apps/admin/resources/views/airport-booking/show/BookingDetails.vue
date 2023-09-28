<script setup lang="ts">

import { computed, onMounted, ref, unref, watch } from 'vue'

import { MaybeRef, useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useCurrencyStore } from '~resources/store/currency'
import { useBookingStore } from '~resources/views/airport-booking/show/store/booking'
import AmountBlock from '~resources/views/booking/components/AmountBlock.vue'
import GuestModal from '~resources/views/booking/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import PriceModal from '~resources/views/booking/components/PriceModal.vue'
import { GuestFormData } from '~resources/views/booking/lib/data-types'
import { useOrderStore } from '~resources/views/booking/store/order'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { addBookingGuest, deleteBookingGuest } from '~api/booking/airport/guests'
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
const orderId = computed(() => orderStore.order.id)
const { getCurrencyByCodeChar } = useCurrencyStore()
// const orderCurrency = computed<Currency | undefined>(() => orderStore.currency)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])
const booking = computed(() => bookingStore.booking)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.price.grossPrice.currency.value),
)
const netCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.price.netPrice.currency.value),
)
const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const [isNetPriceModalOpened, toggleNetPriceModal] = useToggle<boolean>(false)
const [isGrossPriceModalOpened, toggleGrossPriceModal] = useToggle<boolean>(false)
const [isNetPenaltyModalOpened, toggleNetPenaltyModal] = useToggle<boolean>(false)
const [isGrossPenaltyModalOpened, toggleGrossPenaltyModal] = useToggle<boolean>(false)

onMounted(() => {
  fetchCountries()
})

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      console.log(preparedRequest)
      if (preparedRequest && preparedRequest.id !== undefined) {
        const payload = { bookingID, guestId: preparedRequest.id }
        await addBookingGuest(payload)
      } else {
        const payload = { airportBookingId: bookingID, ...preparedRequest }
        payload.orderId = orderId.value
        await addOrderGuest(payload)
      }
      await bookingStore.fetchBooking()
      await orderStore.fetchGuests()
    },
  },
  edit: {
    title: 'Редактирование гостя',
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
    guestForm.value.selectedGuestFromOrder = undefined
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

const handleSaveGrossManualPrice = async (value: number | undefined) => {
  toggleGrossPriceModal(false)
  await updateBookingPrice({
    bookingID,
    grossPrice: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveNetManualPrice = async (value: number | undefined) => {
  toggleNetPriceModal(false)
  await updateBookingPrice({
    bookingID,
    netPrice: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveBoPenalty = async (value: number | undefined) => {
  toggleGrossPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    grossPenalty: value,
  })
  bookingStore.fetchBooking()
}

const handleSaveHoPenalty = async (value: number | undefined) => {
  toggleNetPenaltyModal(false)
  await updateBookingPrice({
    bookingID,
    netPenalty: value,
  })
  bookingStore.fetchBooking()
}

const getDisplayPriceValue = (type: 'gross' | 'net') => {
  if (!booking.value) {
    return 0
  }
  if (type === 'gross') {
    return booking.value?.price.grossPrice.manualValue || booking.value?.price.grossPrice.calculatedValue
  }

  return booking.value?.price.netPrice.manualValue || booking.value?.price.netPrice.calculatedValue
}

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
    @close="closeGuestModal"
    @submit="submitGuestModal"
    @clear="guestForm = getDefaultGuestForm()"
  />

  <Card>
    <CardTitle class="mr-4" title="Информация о брони" />
    <div class="d-flex gap-4">
      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Гости" />
            <IconButton v-if="isEditableStatus" icon="add" @click="openAddGuestModal" />
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
          <div class="d-flex justify-content-between align-items-center">
            <InfoBlockTitle title="Стоимость брони" />
            <div v-if="booking && grossCurrency" class="float-end total-sum">
              Общая сумма:
              <strong>
                {{ formatPrice(getDisplayPriceValue('gross'), grossCurrency.sign) }}
              </strong>
              <span v-if="booking.price.grossPrice.isManual" class="text-muted"> (выставлена вручную)</span>
            </div>
          </div>
        </template>
        <PriceModal
          header="Общая сумма (нетто)"
          :label="`Общая сумма (нетто) в ${netCurrency?.code_char}`"
          :value="booking?.price.netPrice.manualValue || undefined"
          :opened="isNetPriceModalOpened"
          @close="toggleNetPriceModal(false)"
          @submit="handleSaveNetManualPrice"
        />

        <PriceModal
          header="Общая сумма (брутто)"
          :label="`Общая сумма (брутто) ${grossCurrency?.code_char}`"
          :value="booking?.price.grossPrice.manualValue || undefined"
          :opened="isGrossPriceModalOpened"
          @close="toggleGrossPriceModal(false)"
          @submit="handleSaveGrossManualPrice"
        />

        <PriceModal
          header="Сумма штрафа для клиента"
          :label="`Сумма штрафа для клиента в ${grossCurrency?.code_char}`"
          :value="booking?.price.grossPrice.penaltyValue || undefined"
          :opened="isGrossPenaltyModalOpened"
          @close="toggleGrossPenaltyModal(false)"
          @submit="handleSaveBoPenalty"
        />

        <PriceModal
          header="Сумма штрафа от гостиницы"
          :label="`Сумма штрафа от гостиницы в ${netCurrency?.code_char}`"
          :value="booking?.price.netPrice.penaltyValue || undefined"
          :opened="isNetPenaltyModalOpened"
          @close="toggleNetPenaltyModal(false)"
          @submit="handleSaveHoPenalty"
        />
        <div class="d-flex flex-row gap-3">
          <AmountBlock
            v-if="booking"
            title="Приход"
            :currency="grossCurrency"
            amount-title="Общая сумма (брутто)"
            :amount-value="getDisplayPriceValue('gross')"
            penalty-title="Сумма штрафа для клиента"
            :penalty-value="booking.price.grossPenalty"
            :need-show-penalty="(booking?.price.netPenalty || 0) > 0"
            @click-change-price="toggleGrossPriceModal(true)"
            @click-change-penalty="toggleGrossPenaltyModal(true)"
          />

          <AmountBlock
            v-if="booking"
            title="Расход"
            :currency="netCurrency"
            amount-title="Общая сумма (нетто)"
            :amount-value="getDisplayPriceValue('net')"
            penalty-title="Сумма штрафа от гостиницы"
            :penalty-value="booking.price.netPenalty"
            :need-show-penalty="(booking?.price.netPenalty || 0) > 0"
            @click-change-price="toggleNetPriceModal(true)"
            @click-change-penalty="toggleNetPenaltyModal(true)"
          />
        </div>

        <div v-if="booking && grossCurrency && netCurrency" class="mt-2">
          Прибыль = {{ formatPrice(getDisplayPriceValue('gross'), grossCurrency.sign) }} - {{
            formatPrice(getDisplayPriceValue('net'), netCurrency.sign) }} =
          {{
            formatPrice((getDisplayPriceValue('gross') - getDisplayPriceValue('net')), grossCurrency.sign)
          }}
        </div>
      </InfoBlock>
    </div>
  </Card>
</template>

<style lang="scss" scoped>
.total-sum {
  margin-bottom: 0.5rem;
}
</style>
