<script setup lang="ts">
import { ref } from 'vue'

import AddButton from '~resources/components/AddButton.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import DeleteButton from '~resources/components/DeleteButton.vue'
import axios from '~resources/js/app/api'
import { Hotel } from '~resources/lib/models'
import { useUrlParams } from '~resources/lib/url-params'
import { HotelImage } from '~resources/views/hotel/images/models'

const params = useUrlParams()
const isLoaded = ref<boolean>(false)
const images = ref<HotelImage[]>([])
const hotel = ref<Hotel | undefined>()
const filesForm = ref<HTMLFormElement>()

interface FetchImagesResponse {
  images: HotelImage[]
}

const fetchImages = async () => {
  isLoaded.value = false
  const { data: response } = await axios.get<FetchImagesResponse>(`/admin/v1/hotel/${params.hotel}/images`)
  images.value = response.images
  isLoaded.value = true
}

const fetchHotel = async () => {
  const { data: response } = await axios.get<Hotel>(`/admin/v1/hotel/${params.hotel}`)
  hotel.value = response as Hotel
}

const uploadImages = async () => {
  if (!filesForm.value) {
    return
  }
  const formData = new FormData(filesForm.value as HTMLFormElement)
  await axios.post(`/admin/v1/hotel/${params.hotel}/images/upload`, formData, {
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

const removeFile = async (id: string): Promise<void> => {
  await axios.delete(`/admin/v1/hotel/${params.hotel}/images/${id}`)
  // @todo заменить на какой-нибудь нотифай
  alert('Файл удален')
  fetchImages()
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
              multiple="multiple"
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
            <img :src="image.url" :alt="image.name" class="w-100 h-100">
          </div>
          <div class="body">
            <div class="buttons">
              <DeleteButton @click="removeFile(image.id)" />
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
    flex-basis: 30%;
    flex-grow: 1;
    overflow: hidden;
}

.card .image {
    height: 200px;
    position: relative;
}

.card .body {
    padding: 0.5rem 1rem;
}

.card .body .buttons {
    margin-top: 0.5rem;
    display: flex;
    gap: 1rem;
}
</style>
