<script setup lang="ts">
import { computed, ref } from 'vue'

import linkIcon from '@mdi/svg/svg/link.svg'
import linkOffIcon from '@mdi/svg/svg/link-off.svg'
import plusIcon from '@mdi/svg/svg/plus.svg'
import trashIcon from '@mdi/svg/svg/trash-can-outline.svg'
import { useArrayFind, useUrlSearchParams } from '@vueuse/core'

import BaseButton from '~resources/components/BaseButton.vue'
import BaseDialog from '~resources/components/BaseDialog.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import ImageZoom from '~resources/components/ImageZoom.vue'
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

const images = computed<HotelImage[] | null>(() => imagesData.value)

const {
  execute: fetchRoomImages,
  data: roomImagesData,
  isFetching: isRoomImagesFetching,
} = useHotelRoomImagesAPI({ hotelID, roomID })

const roomImages = computed<RoomImage[]>(() => {
  if (roomImagesData.value === null) return []
  return roomImagesData.value
})

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

const isUploadDialogOpened = ref(false)

const removeImage = (imageID: number) => {
  const api = useHotelImageRemoveAPI({ hotelID, imageID })
  api.execute()
  api.onFetchResponse(() => {
    // eslint-disable-next-line no-alert
    alert('Файл удален')
    fetchImages()
  })
}

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

const title = computed<string>(() => {
  if (roomID && room.value) return room.value.display_name
  if (hotel.value) return hotel.value.name
  return ''
})
</script>
<template>
  <BaseLayout v-if="isHotelFetching" title="Loading" />
  <BaseLayout v-else :title="title">
    <template #header-controls>
      <BaseButton
        label="Добавить фотографии"
        :start-icon="plusIcon"
        severity="primary"
        @click="isUploadDialogOpened = true"
      />
      <BaseDialog
        :opened="isUploadDialogOpened"
        @close="isUploadDialogOpened = false"
      >
        <template #title>
          <div>Добавить фотографии</div>
        </template>
        <form
          id="form"
          ref="filesForm"
          method="post"
          enctype="multipart/form-data"
          @submit.prevent="uploadImages"
        >
          <label>
            Файлы
            <input required type="file" multiple name="files[]">
          </label>
        </form>
        <template #actions>
          <BaseButton
            severity="primary"
            variant="filled"
            label="Отправить"
            type="submit"
            form="form"
          />
        </template>
      </BaseDialog>
    </template>

    <div v-if="isRoomFetching || isImagesFetching || isRoomImagesFetching">
      Loading
    </div>
    <div v-else-if="images === null">
      No images
    </div>
    <div v-else class="images">
      <div
        v-for="image in images"
        :key="image.id"
        class="image"
        :class="{ hidden: isNeedHideImage(image.id) }"
      >
        <ImageZoom
          class="picture"
          :src="image.file.url"
          :alt="image.file.name"
        />
        <div class="actions">
          <div class="actionsStart">
            <template v-if="roomID">
              <BaseButton
                v-if="!getRoomImage(image.id)"
                severity="primary"
                label="Привязать к номеру"
                :start-icon="linkIcon"
                @click="setImageToRoom(image.id)"
              />
              <BaseButton
                v-else
                label="Отвязать от номера"
                :start-icon="linkOffIcon"
                @click="deleteRoomImage(image.id)"
              />
            </template>
          </div>
          <div class="actionsEnd">
            <BaseButton
              v-if="!getRoomImage(image.id)"
              label="Удалить"
              severity="danger"
              :only-icon="trashIcon"
              @click="removeImage(image.id)"
            />
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>
<style lang="scss" scoped>
.images {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1em;
}

.image {
  display: flex;
  flex-flow: column;
  border: 1px solid lightgray;
  border-radius: 0.375em;
}

.picture {
  object-fit: cover;
  object-position: center;
  max-width: 100%;
  border-top-left-radius: inherit;
  border-top-right-radius: inherit;
  aspect-ratio: 4/3;
}

.actions {
  display: flex;
  padding: 0.5em;
}

.actionsStart {
  flex-grow: 1;
}

.actionsEnd {
  flex-shrink: 0;
}
</style>
