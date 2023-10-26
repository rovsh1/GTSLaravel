<script setup lang="ts">

import { computed, MaybeRef, onMounted, reactive, ref, unref, watch } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import { useCurrencyStore } from '~resources/store/currency'
import GuestModal from '~resources/views/booking/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { getConditionLabel } from '~resources/views/booking/lib/constants'
import { GuestFormData, RoomFormData } from '~resources/views/booking/lib/data-types'
import { useOrderStore } from '~resources/views/booking/store/order'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import RoomModal from '~resources/views/hotel-booking/show/components/RoomModal.vue'
import RoomPriceModal from '~resources/views/hotel-booking/show/components/RoomPriceModal.vue'
import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'

import {
  HotelBookingDetails,
  HotelRoomBooking,
  RoomBookingDayPrice,
  RoomBookingPrice,
  RoomInfo,
} from '~api/booking/hotel/details'
import { updateRoomBookingPrice } from '~api/booking/hotel/price'
import { addGuestToBooking, deleteBookingGuest, deleteBookingRoom } from '~api/booking/hotel/rooms'
import { addOrderGuest, Guest, updateOrderGuest } from '~api/booking/order/guest'
import { useCountrySearchAPI } from '~api/country'
import { MarkupSettings } from '~api/hotel/markup-settings'
import { HotelRate, useHotelRatesAPI } from '~api/hotel/price-rate'
import { Currency } from '~api/models'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'
import { formatPrice } from '~lib/price'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import BootstrapCardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import IconButton from '~components/IconButton.vue'
import InlineIcon from '~components/InlineIcon.vue'

const [isOpenedPriceDetailsModal, toggleModalPriceDetails] = useToggle()
const [isShowRoomModal, toggleRoomModal] = useToggle()
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

const bookingDetails = computed<HotelBookingDetails | undefined>(() => bookingStore.booking?.details)
const markupSettings = computed<MarkupSettings | null>(() => bookingStore.markupSettings)
const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)
const grossCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.grossPrice.currency.value),
)
const netCurrency = computed<Currency | undefined>(
  () => getCurrencyByCodeChar(bookingStore.booking?.prices.netPrice.currency.value),
)
const orderId = computed(() => orderStore.order.id)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])

const bookingRoomsGuestsIds = computed<number[]>(() => bookingDetails.value?.roomBookings.flatMap(
  (roomBooking) => roomBooking.guestIds || [],
) || [])

const filteredOrderGuests = computed<Guest[]>(() => orderGuests.value.filter((guest) =>
  !bookingRoomsGuestsIds.value.includes(guest.id)))

const isBookingPriceManual = computed(
  () => bookingStore.booking?.prices.grossPrice.isManual || bookingStore.booking?.prices.netPrice.isManual,
)

const canChangeRoomPrice = computed<boolean>(
  () => (bookingStore.availableActions?.canChangeRoomPrice && !isBookingPriceManual.value) || false,
)

const selectedRoomDetails = reactive({
  roomDayPrices: <RoomBookingDayPrice[]> [],
  roomInfo: <RoomInfo | null> null,
})

const { execute: fetchPriceRates, data: priceRates } = useHotelRatesAPI({ hotelID })
const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const getPriceRateName = (id: number): string | undefined =>
  priceRates.value?.find((priceRate: HotelRate) => priceRate.id === id)?.name

const getDefaultGuestForm = () => ({ isAdult: true })

const editRoomBookingId = ref<number>()

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      if (!editRoomBookingId.value) return
      const preparedRequest = unref(request)
      if (preparedRequest && preparedRequest.id !== undefined) {
        const payload = { bookingID, roomBookingId: editRoomBookingId.value, guestId: preparedRequest.id }
        await addGuestToBooking(payload)
      } else {
        const payload = { hotelBookingId: bookingID, hotelBookingRoomId: editRoomBookingId.value, ...preparedRequest }
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
      payload.orderId = orderId.value
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

const roomForm = ref<Partial<RoomFormData>>({})
const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())

watch(editableGuest, (value) => {
  if (!value) {
    guestForm.value = getDefaultGuestForm()
    guestForm.value.selectedGuestFromOrder = undefined
    return
  }
  guestForm.value = value
})

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
  await fetchBooking()
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

const onCloseModal = () => {
  roomForm.value = {}
  toggleRoomModal(false)
}

const onRoomModalSubmit = async () => {
  toggleRoomModal(false)
  await fetchBooking()
}

const getMinDayPrices = (dayPrices: RoomBookingDayPrice[]): number | null => {
  const minPrice = dayPrices.length > 0 ? dayPrices.reduce((min, dayPrice) =>
    ((dayPrice.grossValue < min) ? dayPrice.grossValue : min), dayPrices[0].grossValue) : null
  return minPrice
}

const getMaxDayPrices = (dayPrices: RoomBookingDayPrice[]): number | null => {
  const maxPrice = dayPrices.length > 0 ? dayPrices.reduce((max, dayPrice) =>
    ((dayPrice.grossValue > max) ? dayPrice.grossValue : max), dayPrices[0].grossValue) : null
  return maxPrice
}

onMounted(() => {
  fetchPriceRates()
  fetchCountries()
})

</script>

<template>
  <RoomModal
    :opened="isShowRoomModal"
    :form-data="roomForm"
    :room-booking-id="editRoomBookingId"
    :hotel-markup-settings="markupSettings"
    @close="onCloseModal"
    @submit="onRoomModalSubmit"
  />

  <GuestModal
    v-if="countries"
    :title-text="guestModalTitle"
    :opened="isGuestModalOpened"
    :is-fetching="isGuestModalLoading"
    :form-data="guestForm"
    :order-guests="filteredOrderGuests"
    :countries="countries"
    @close="closeGuestModal()"
    @submit="submitGuestModal"
    @clear="guestForm = getDefaultGuestForm()"
  />

  <RoomPriceModal
    :booking-id="bookingID"
    :room-price="editableRoomPrice"
    :opened="isShowRoomPriceModal"
    :gross-currency="grossCurrency"
    :net-currency="netCurrency"
    @close="toggleRoomPriceModal(false)"
    @submit="({ grossPrice, netPrice }) => handleUpdateRoomPrice(grossPrice, netPrice)"
    @clear="guestForm = {}"
  />

  <div class="mt-3" />
  <BootstrapCard v-for="room in bookingDetails?.roomBookings" :key="room.id">
    <div class="d-flex align-items-center">
      <BootstrapCardTitle class="mr-4" :title="room.roomInfo.name" />
      <div class="pt-card-title">
        <EditTableRowButton
          v-if="isEditableStatus"
          @edit="handleEditRoom(room.id, room)"
          @delete="handleDeleteRoom(room.id)"
        />
      </div>
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
        <div v-if="grossCurrency" class="d-flex flex-row justify-content-between w-100 mt-2">
          <span class="prices-information">
            <strong>
              Итого: {{ formatPrice(room.price.grossValue, grossCurrency.sign) }}
            </strong>
            <br>
            <strong class="prices-information-details">
              <span>Цена за ночь: </span>
              <span v-if="getMinDayPrices(room.price.dayPrices) === getMaxDayPrices(room.price.dayPrices)">
                {{ formatPrice(getMinDayPrices(room.price.dayPrices) as number, grossCurrency.sign) }}
              </span>
              <span v-else>
                от {{ formatPrice(getMinDayPrices(room.price.dayPrices) as number, grossCurrency.sign) }}
                до {{ formatPrice(getMaxDayPrices(room.price.dayPrices) as number, grossCurrency.sign) }}
              </span>
              <button
                v-tooltip="'Подробнее'"
                type="button"
                class="prices-information-details-button"
                @click="() => {
                  selectedRoomDetails.roomInfo = room.roomInfo
                  selectedRoomDetails.roomDayPrices = room.price.dayPrices
                  toggleModalPriceDetails()
                }"
              >
                <InlineIcon icon="info" class="prices-information-details-button-icon" />
              </button>
              <span v-if="room.price.grossDayValue" v-tooltip="'Цена за номер выставлена вручную'" class="prices-information-details-info">
                <InlineIcon icon="touch_app" class="prices-information-details-info-icon" />
              </span>
            </strong>
          </span>
          <a v-if="canChangeRoomPrice" href="#" @click.prevent="handleEditRoomPrice(room.id, room.price)">
            Изменить цену номера
          </a>
        </div>
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Список гостей" />
            <IconButton
              v-if="isEditableStatus && (room.roomInfo.guestsCount && room.guestIds.length < room.roomInfo.guestsCount)"
              icon="add"
              @click="() => {
                editRoomBookingId = room.id
                openAddGuestModal()
              }"
            />
          </div>
        </template>

        <GuestsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :guest-ids="room.guestIds"
          :order-guests="orderGuests"
          :countries="countries"
          @edit="(guest) => {
            editRoomBookingId = room.id
            openEditGuestModal(guest.id, guest)
          }"
          @delete="(guest) => handleDeleteGuest(room.id, guest.id)"
        />
      </InfoBlock>
    </div>
  </BootstrapCard>

  <div>
    <BootstrapButton v-if="isEditableStatus" label="Добавить номер" @click="handleAddRoom" />
  </div>
  <BaseDialog :opened="isOpenedPriceDetailsModal as boolean" :auto-width="true" @close="toggleModalPriceDetails(false)">
    <template #title>
      Подробный расчет по номеру
      ({{ selectedRoomDetails.roomInfo ? selectedRoomDetails.roomInfo.name : '---' }})
    </template>
    <table class="table">
      <thead>
        <tr>
          <th class="text-nowrap">Дата</th>
          <th class="text-nowrap">Цена за ночь</th>
          <th class="text-nowrap">Подробный расчет</th>
        </tr>
      </thead>
      <tbody v-if="grossCurrency">
        <tr v-for="dayPrice in selectedRoomDetails.roomDayPrices" :key="dayPrice.date">
          <td class="text-nowrap">
            {{ dayPrice.date }}
          </td>
          <td class="text-nowrap">
            {{ formatPrice(dayPrice.grossValue, grossCurrency.sign) }}
          </td>
          <td class="text-nowrap">{{ dayPrice.grossFormula }}</td>
        </tr>
      </tbody>
    </table>
    <template #actions-end>
      <button class="btn btn-cancel" type="button" @click="toggleModalPriceDetails(false)">Отмена</button>
    </template>
  </BaseDialog>
</template>

<style lang="scss" scoped>
.prices-information {
  .prices-information-details {
    font-weight: 400;
    font-style: italic;

    & > * {
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
  padding-top: 0;
}
</style>
