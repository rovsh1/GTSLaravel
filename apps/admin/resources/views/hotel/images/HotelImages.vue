<script setup lang="ts">
import { computed, ref } from 'vue'

import { useArrayFind, useUrlSearchParams } from '@vueuse/core'

import AddButton from '~resources/components/AddButton.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import DeleteButton from '~resources/components/DeleteButton.vue'
import {
  useHotelAPI, useHotelImageRemoveAPI,
  useHotelImagesListAPI, useHotelImagesUploadAPI,
  useHotelRoomAPI,
  useHotelRoomImageCreateAPI, useHotelRoomImageDeleteAPI, useHotelRoomImagesAPI,
} from '~resources/lib/api/hotel'
import { Hotel, Room } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'
import { HotelImage, RoomImage } from '~resources/views/hotel/images/models'

const { hotel: hotelID } = useUrlParams()
const { room_id: roomID } = useUrlSearchParams<{ room_id?: number }>()

const {
  execute: fetchImages,
  data: imagesData,
  isFetching: isImagesFetching,
} = useHotelImagesListAPI({ hotelID })

const images = computed<HotelImage[]>(() => {
  if (imagesData.value === null) return []
  return imagesData.value
})

const {
  execute: fetchRoomImages,
  data: roomImagesData,
  isFetching: isRoomImagesFetching,
} = useHotelRoomImagesAPI({ hotelID, roomID })

const roomImages = computed<RoomImage[]>(() => {
  if (roomImagesData.value === null) return []
  return roomImagesData.value
})

const isRoomImagesLoaded = computed(() => !isRoomImagesFetching)

const {
  execute: fetchHotel,
  data: hotelData,
  isFetching: isHotelFetching,
} = useHotelAPI({ hotelID })

const hotel = computed<Hotel | null>(() => hotelData.value)

const {
  execute: fetchRoom,
  data: roomData,
  isFetching: isRoomFetching,
} = useHotelRoomAPI({ hotelID, roomID })

const room = computed<Room | null>(() => roomData.value)

const filesForm = ref<HTMLFormElement>()

const uploadImages = async () => {
  if (!filesForm.value) {
    return
  }
  await useHotelImagesUploadAPI({
    hotelID,
    roomID,
    filesForm: filesForm.value,
  }).execute()
  filesForm.value?.reset()
  await fetchImages()
  await fetchRoomImages()
}

const showUploadModal = async () => {
  console.log('clicked')
  // @todo показать модалку с аплоадером файлов
}

const removeImage = (imageID: number) => {
  const api = useHotelImageRemoveAPI({ hotelID, imageID })
  api.execute()
  api.onFetchResponse(() => {
    // eslint-disable-next-line no-alert
    alert('Файл удален')
    fetchImages()
  })
}

// @todo добавить drag n drop сортировку
// @todo отправить запрос на пересортировку
// const {} = useHotelImagesReorderAPI({ hotelID, images })

const setImageToRoom = async (imageID: number) => {
  if (!roomID) {
    return
  }
  await useHotelRoomImageCreateAPI({ hotelID, roomID, imageID })
    .execute()
  await fetchRoomImages()
  await fetchImages()
}

const deleteRoomImage = async (imageID: number) => {
  if (!roomID) {
    return
  }
  await useHotelRoomImageDeleteAPI({ hotelID, roomID, imageID })
    .execute()
  await fetchRoomImages()
  await fetchImages()
}

const isLoaded = computed(() => (
  !isImagesFetching && !isHotelFetching && !isRoomImagesFetching && !isRoomFetching
))

const getRoomImage = (id: number): RoomImage | undefined => {
  if (roomImages.value.length === 0) {
    return undefined
  }
  const existRoomImage = useArrayFind(
    roomImages,
    (image) => image.image_id === id,
  )
  return existRoomImage.value
}

const isNeedHideImage = (id: number): boolean => {
  if (!roomID) {
    return false
  }

  return !getRoomImage(id)
}

fetchRoom()
fetchRoomImages()
fetchHotel()
fetchImages()

</script>

<template>
  <BaseLayout
    v-if="hotel"
    :title="roomID && room?.display_name ? room.display_name : hotel.name"
  >
    <template #header-controls>
      <AddButton
        text="Добавить фотографии"
        @click="showUploadModal"
      />
    </template>

    <template #body>
      <div class="bg-info mb-2">
        <h5>Временная форма - todo заменить на file uploader</h5>
        <form
          ref="filesForm"
          method="post"
          enctype="multipart/form-data"
          @submit.prevent="uploadImages"
        >
          <label>
            Файлы
            <input
              required
              type="file"
              multiple
              name="files[]"
            >
          </label>

          <button type="submit">
            Отправить
          </button>
        </form>
      </div>

      <div v-if="isLoaded && isRoomImagesLoaded" class="cards">
        <div
          v-for="image in images"
          :key="image.id"
          :class="{ card: true, hidden: isNeedHideImage(image.id) }"
        >
          <div class="edit-info" />
          <div class="image">
            <img
              :src="image.file.url"
              :alt="image.file.name"
              class="w-100 h-100"
            >
          </div>
          <div class="body">
            <div class="buttons">
              <DeleteButton
                v-if="!getRoomImage(image.id)"
                @click="removeImage(image.id)"
              />
              <div v-if="roomID">
                <button
                  v-if="!getRoomImage(image.id)"
                  type="button"
                  class="btn btn-primary"
                  @click="setImageToRoom(image.id)"
                >
                  <i class="icon">link</i>
                  Привязать к номеру
                </button>
                <button
                  v-else
                  type="button"
                  class="btn btn-light"
                  @click="deleteRoomImage(image.id)"
                >
                  <i class="icon">link_off</i>
                  Отвязать от номера
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </BaseLayout>
</template>

<style scoped>
.btn > i {
  margin-right: 4px;
  font-size: 20px
}

.cards {
  display: flex;
  flex-wrap: wrap;
  gap: 32px;
}

.cards .card {
  flex-grow: 1;
  flex-basis: 30%;
  overflow: hidden;
}

.card .image {
  position: relative;
  height: 200px;
}

.card.hidden .image {
  opacity: 0.3;
}

.card .body {
  padding: 0.5rem;
}

.card .body .buttons {
  display: flex;
  gap: 1rem;
}
</style>
