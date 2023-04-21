<script setup lang="ts">
import { reactive, ref } from 'vue'

import { useArrayFind, useUrlSearchParams } from '@vueuse/core'

import AddButton from '~resources/components/AddButton.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import DeleteButton from '~resources/components/DeleteButton.vue'
import api from '~resources/js/app/api'
import adminApi from '~resources/js/app/api/admin'
import { Hotel as IHotel, Room as IRoom } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'
import { HotelImage, RoomImage } from '~resources/views/hotel/images/models'

const { hotel: hotelId } = useUrlParams()
const { room_id: roomId } = useUrlSearchParams()
const isLoaded = ref<boolean>(false)
const isRoomImagesLoaded = ref<boolean>(false)
let images = reactive<HotelImage[]>([])
let roomImages = reactive<RoomImage[]>([])
const hotel = ref<IHotel>()
const room = ref<IRoom | undefined>()
const filesForm = ref<HTMLFormElement>()

const fetchImages = async () => {
  isLoaded.value = false
  const { data: response } = await adminApi
    .get<HotelImage[]>(`/hotels/${hotelId}/images/list`)
  images = response
  isLoaded.value = true
}

const fetchRoomImages = async () => {
  if (!roomId) {
    isRoomImagesLoaded.value = true
    return
  }
  isRoomImagesLoaded.value = false
  const { data: response } = await adminApi
    .get<RoomImage[]>(`/hotels/${hotelId}/images/${roomId}/list`)
  roomImages = response
  isRoomImagesLoaded.value = true
}

const fetchHotel = async () => {
  const { data: response } = await api
    .get<IHotel>(`/admin/v1/hotel/${hotelId}`)
  hotel.value = response
}

const fetchRoom = async () => {
  if (!roomId) {
    return
  }
  const { data: response } = await api
    .get<IRoom>(`/admin/v1/hotel/${hotelId}/room/${roomId}`)
  room.value = response
}

const uploadImages = async () => {
  if (!filesForm.value) {
    return
  }
  const formData = new FormData(filesForm.value as HTMLFormElement)
  if (roomId) {
    formData.append('room_id', String(roomId))
  }
  await adminApi.post(`/hotels/${hotelId}/images/upload`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  filesForm.value?.reset()
  await fetchImages()
  await fetchRoomImages()
}

const showUploadModal = async () => {
  console.log('clicked')
  // @todo показать модалку с аплоадером файлов
}

const removeImage = async (id: number): Promise<void> => {
  await adminApi.delete(`/hotels/${hotelId}/images/${id}`)
  // @todo заменить на какой-нибудь нотифай
  // eslint-disable-next-line no-alert
  alert('Файл удален')
  fetchImages()
}

// @todo добавить drag n drop сортировку
// eslint-disable-next-line unused-imports/no-unused-vars
const reorderImages = async (files: HotelImage[]): Promise<void> => {
  // @todo отправить запрос на пересортировку
  await adminApi.post(`/hotels/${hotelId}/images/reorder`, files)
}

const setImageToRoom = async (imageId: number) => {
  if (!roomId) {
    return
  }
  // eslint-disable-next-line vue/max-len
  await adminApi.post(`/hotels/${hotelId}/rooms/${roomId}/images/${imageId}/create`)
  await fetchRoomImages()
  await fetchImages()
}

const deleteRoomImage = async (imageId: number) => {
  if (!roomId) {
    return
  }
  // eslint-disable-next-line vue/max-len
  await adminApi.post(`/hotels/${hotelId}/rooms/${roomId}/images/${imageId}/delete`)
  await fetchRoomImages()
  await fetchImages()
}

const getRoomImage = (id: number): RoomImage | undefined => {
  if (roomImages.length === 0) {
    return undefined
  }
  const existRoomImage = useArrayFind(
    roomImages,
    (image) => image.image_id === id,
  )
  return existRoomImage.value
}

const isNeedHideImage = (id: number): boolean => {
  if (!roomId) {
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
  <BaseLayout v-if="hotel" :title="roomId && room?.display_name ? room.display_name : hotel.name">
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
              <div v-if="roomId">
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
  opacity: .3;
}

.card .body {
  padding: 0.5rem 1rem;
}

.card .body .buttons {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
}
</style>
