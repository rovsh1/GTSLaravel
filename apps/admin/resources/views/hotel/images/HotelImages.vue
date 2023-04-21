<script setup lang="ts">
import { ref } from 'vue'

import { useUrlSearchParams } from '@vueuse/core'

import AddButton from '~resources/components/AddButton.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import DeleteButton from '~resources/components/DeleteButton.vue'
import api from '~resources/js/app/api'
import adminApi from '~resources/js/app/api/admin'
import { Hotel } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'
import { HotelImage } from '~resources/views/hotel/images/models'

const params = useUrlParams()
const { room_id: roomId } = useUrlSearchParams()
const isLoaded = ref<boolean>(false)
const images = ref<HotelImage[]>([])
const hotel = ref<Hotel | undefined>()
const filesForm = ref<HTMLFormElement>()

console.log(roomId)

interface FetchImagesResponse {
  images: HotelImage[]
}

const fetchImages = async () => {
  isLoaded.value = false
  const { data: response } = await adminApi
    .get<FetchImagesResponse>(`/hotels/${params.hotel}/images/list`)
  images.value = response.images
  isLoaded.value = true
}

const fetchHotel = async () => {
  const { data: response } = await api
    .get<Hotel>(`/admin/v1/hotel/${params.hotel}`)
  hotel.value = response as Hotel
}

const uploadImages = async () => {
  if (!filesForm.value) {
    return
  }
  const formData = new FormData(filesForm.value as HTMLFormElement)
  await adminApi.post(`/hotels/${params.hotel}/images/upload`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
  filesForm.value?.reset()
  fetchImages()
}

const showUploadModal = async () => {
  console.log('clicked')
  // @todo показать модалку с аплоадером файлов
}

const removeImage = async (id: number): Promise<void> => {
  await adminApi.delete(`/hotels/${params.hotel}/images/${id}`)
  // @todo заменить на какой-нибудь нотифай
  // eslint-disable-next-line no-alert
  alert('Файл удален')
  fetchImages()
}

// @todo добавить drag n drop сортировку
// eslint-disable-next-line unused-imports/no-unused-vars
const reorderImages = async (files: HotelImage[]): Promise<void> => {
  // @todo отправить запрос на пересортировку
  await adminApi.post(`/hotels/${params.hotel}/images/reorder`, files)
}

fetchImages()
fetchHotel()

</script>

<template>
  <BaseLayout v-if="hotel" :title="hotel.name">
    <template #header-controls>
      <AddButton
        text="Добавить фотографию"
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

      <div v-if="isLoaded" class="cards">
        <div v-for="image in images" :key="image.id" class="card">
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
              <DeleteButton @click="removeImage(image.id)" />
            </div>
          </div>
        </div>
      </div>
    </template>
  </BaseLayout>
</template>

<style scoped>
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

.card .body {
  padding: 0.5rem 1rem;
}

.card .body .buttons {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
}
</style>
