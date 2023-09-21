<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useCurrencyStore } from '~resources/store/currency'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import GuestModal from '~resources/views/hotel-booking/show/components/GuestModal.vue'
import GuestsTable from '~resources/views/hotel-booking/show/components/GuestsTable.vue'
import InfoBlock from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlockTitle.vue'
import RoomModal from '~resources/views/hotel-booking/show/components/RoomModal.vue'
import RoomPriceModal from '~resources/views/hotel-booking/show/components/RoomPriceModal.vue'
import { getConditionLabel } from '~resources/views/hotel-booking/show/lib/constants'
import { GuestFormData, RoomFormData } from '~resources/views/hotel-booking/show/lib/data-types'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order-currency'

import {
  HotelBookingDetails,
  HotelBookingGuest,
  HotelRoomBooking,
  RoomBookingPrice,
} from '~api/booking/hotel/details'
import { updateRoomBookingPrice } from '~api/booking/hotel/price'
import { deleteBookingGuest, deleteBookingRoom } from '~api/booking/hotel/rooms'
import { Guest } from '~api/booking/order/guest'
import { CountryResponse, useCountrySearchAPI } from '~api/country'
import { MarkupSettings } from '~api/hotel/markup-settings'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'
import { Currency } from '~api/models'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { formatDate } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'
import { formatPrice } from '~lib/price'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import BootstrapCardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import IconButton from '~components/IconButton.vue'

const [isShowRoomModal, toggleRoomModal] = useToggle()
const [isShowGuestModal, toggleGuestModal] = useToggle()
const [isShowRoomPriceModal, toggleRoomPriceModal] = useToggle()

const { bookingID, hotelID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    hotelID: z.number(),
    bookingID: z.number(),
  }),
)

const { getCurrencyByCodeChar } = useCurrencyStore()
const bookingStore = useBookingStore()
const { fetchBooking, fetchAvailableActions } = bookingStore
const orderStore = useOrderStore()

const bookingDetails = computed<HotelBookingDetails | null>(() => bookingStore.booking)
const markupSettings = computed<MarkupSettings | null>(() => bookingStore.markupSettings)
const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.price.grossPrice.currency.value),
)
const netCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.price.netPrice.currency.value),
)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])
const isBookingPriceManual = computed(
  () => bookingStore.booking?.price.grossPrice.isManual || bookingStore.booking?.price.netPrice.isManual,
)
const canChangeRoomPrice = computed<boolean>(
  () => (bookingStore.availableActions?.canChangeRoomPrice && !isBookingPriceManual.value) || false,
)

const { execute: fetchPriceRates, data: priceRates } = useHotelRatesAPI({ hotelID })
const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const getPriceRateName = (id: number): string | undefined =>
  priceRates.value?.find((priceRate: HotelRate) => priceRate.id === id)?.name

const getDefaultGuestForm = () => ({ isAdult: true })

const roomForm = ref<Partial<RoomFormData>>({})
const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())

const editRoomBookingId = ref<number>()
const editGuestId = ref<number>()
const handleAddRoomGuest = (roomBookingId: number) => {
  editRoomBookingId.value = roomBookingId
  editGuestId.value = undefined
  guestForm.value = getDefaultGuestForm()
  toggleGuestModal(true)
}

const handleEditGuest = (roomBookingId: number, guest: HotelBookingGuest): void => {
  editRoomBookingId.value = roomBookingId
  editGuestId.value = guest.id
  guestForm.value = guest
  toggleGuestModal(true)
}

const handleDeleteGuest = async (roomBookingId: number, guestId: number): Promise<void> => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить гостя?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingGuest({ bookingID, roomBookingId, guestId })
    await fetchBooking()
    toggleClose()
  }
}

const handleAddRoom = (): void => {
  editRoomBookingId.value = undefined
  roomForm.value = {}
  toggleRoomModal()
  fetchAvailableActions()
}

const handleEditRoom = (roomBookingId: number, room: HotelRoomBooking): void => {
  editRoomBookingId.value = roomBookingId
  roomForm.value = {
    id: room.roomInfo.id,
    discount: room.details.discount,
    rateId: room.details.rateId,
    earlyCheckIn: room.details.earlyCheckIn,
    lateCheckOut: room.details.lateCheckOut,
    residentType: room.details.isResident ? 1 : 0,
    note: room.details.guestNote,
  }
  toggleRoomModal()
}

const handleDeleteRoom = async (roomBookingId: number): Promise<void> => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить номер?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingRoom({ bookingID, roomBookingId })
    await fetchBooking()
    toggleClose()
  }
}

const editableRoomPrice = ref<RoomBookingPrice>()
const handleEditRoomPrice = (roomBookingId: number, roomPrice: RoomBookingPrice): void => {
  editRoomBookingId.value = roomBookingId
  editableRoomPrice.value = roomPrice
  toggleRoomPriceModal(true)
}

const handleUpdateRoomPrice = async (boPrice: number | undefined | null, hoPrice: number | undefined | null) => {
  toggleRoomPriceModal(false)
  await updateRoomBookingPrice({
    bookingID,
    roomBookingId: editRoomBookingId.value as number,
    grossPrice: boPrice,
    netPrice: hoPrice,
  })
  fetchBooking()
}

const getCheckInTime = (room: HotelRoomBooking) => {
  if (room.details.earlyCheckIn) {
    return getConditionLabel(room.details.earlyCheckIn)
  }

  return `с ${bookingDetails.value?.hotelInfo.checkInTime}`
}

const getCheckOutTime = (room: HotelRoomBooking) => {
  if (room.details.lateCheckOut) {
    return getConditionLabel(room.details.lateCheckOut)
  }

  return `до ${bookingDetails.value?.hotelInfo.checkOutTime}`
}

const onModalSubmit = () => {
  toggleRoomModal(false)
  toggleGuestModal(false)
  fetchBooking()
}

const onCloseModal = () => {
  roomForm.value = {}
  toggleRoomModal(false)
}

fetchPriceRates()
fetchCountries()

</script>

<template>
  <RoomModal
    :opened="isShowRoomModal"
    :form-data="roomForm"
    :room-booking-id="editRoomBookingId"
    :hotel-markup-settings="markupSettings"
    @close="onCloseModal"
    @submit="onModalSubmit"
  />

  <GuestModal
    v-if="countries && editRoomBookingId !== undefined"
    :opened="isShowGuestModal"
    :room-booking-id="editRoomBookingId"
    :guest-id="editGuestId"
    :form-data="guestForm"
    :data-for-select-tab="[]"
    :countries="countries as CountryResponse[]"
    @close="toggleGuestModal(false)"
    @submit="onModalSubmit"
  />

  <RoomPriceModal
    :booking-id="bookingID"
    :room-price="editableRoomPrice"
    :opened="isShowRoomPriceModal"
    :gross-currency="grossCurrency"
    :net-currency="netCurrency"
    @close="toggleRoomPriceModal(false)"
    @submit="({ grossPrice, netPrice }) => handleUpdateRoomPrice(grossPrice, netPrice)"
  />

  <div class="mt-3" />
  <BootstrapCard
    v-for="room in bookingDetails?.roomBookings"
    :key="room.id"
  >
    <div class="d-flex">
      <BootstrapCardTitle class="mr-4" :title="room.roomInfo.name" />
      <EditTableRowButton
        v-if="isEditableStatus"
        @edit="handleEditRoom(room.id, room)"
        @delete="handleDeleteRoom(room.id)"
      />
    </div>
    <div class="d-flex flex-row gap-4">
      <InfoBlock>
        <template #header>
          <InfoBlockTitle title="Параметры размещения" />
        </template>
        <table class="table-params">
          <tbody>
            <tr>
              <th>Тип стоимости</th>
              <td>{{ room.details.isResident ? 'Резидент' : 'Не резидент' }}</td>
            </tr>
            <tr>
              <th>Тариф</th>
              <td>{{ getPriceRateName(room.details.rateId) }}</td>
            </tr>
            <tr>
              <th>Скидка</th>
              <td>{{ room.details.discount || 'Не указано' }}</td>
            </tr>
            <tr>
              <th>Время заезда</th>
              <td>{{ getCheckInTime(room) }}</td>
            </tr>
            <tr>
              <th>Время выезда</th>
              <td>{{ getCheckOutTime(room) }}</td>
            </tr>
            <tr>
              <th>Примечание (запрос в отель, ваучер)</th>
              <td>{{ room.details.guestNote }}</td>
            </tr>
          </tbody>
        </table>

        <div class="mt-2">
          <table class="table">
            <thead>
              <tr>
                <th />
                <th class="text-nowrap">Дата</th>
                <th class="text-nowrap">Цена за ночь</th>
                <th class="text-nowrap">Подробный расчет</th>
              </tr>
            </thead>
            <tbody v-if="grossCurrency">
              <tr v-for="dayPrice in room.price.dayPrices" :key="dayPrice.date">
                <td>Стоимость брутто</td>
                <td class="text-nowrap">
                  {{ formatDate(dayPrice.date) }}
                </td>
                <td class="text-nowrap">
                  {{ formatPrice(dayPrice.grossValue, grossCurrency.sign) }}
                </td>
                <td class="text-nowrap">{{ dayPrice.grossFormula }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="grossCurrency" class="d-flex flex-row justify-content-between w-100 mt-2">
          <strong>
            Итого: {{ formatPrice(room.price.grossValue, grossCurrency.sign) }}
          </strong>
          <a
            v-if="canChangeRoomPrice"
            href="#"
            @click.prevent="handleEditRoomPrice(room.id, room.price)"
          >
            Изменить цену номера
          </a>
        </div>
        <span v-if="room.price.grossDayValue" class="text-muted">(цена за номер выставлена вручную)</span>
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Список гостей" />
            <IconButton
              v-if="isEditableStatus"
              icon="add"
              @click="handleAddRoomGuest(room.id)"
            />
          </div>
        </template>

        <GuestsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :guests="room.guests"
          :order-guests="orderGuests"
          :countries="countries"
          @edit="(guest) => handleEditGuest(room.id, guest)"
          @delete="(guest) => handleDeleteGuest(room.id, guest.id)"
        />
      </InfoBlock>
    </div>
  </BootstrapCard>

  <div>
    <BootstrapButton
      v-if="isEditableStatus"
      label="Добавить номер"
      @click="handleAddRoom"
    />
  </div>
</template>
