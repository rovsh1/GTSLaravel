<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'

import dragIcon from '@mdi/svg/svg/drag.svg'
import linkIcon from '@mdi/svg/svg/link.svg'
import linkOffIcon from '@mdi/svg/svg/link-off.svg'
import plusIcon from '@mdi/svg/svg/plus.svg'
import trashIcon from '@mdi/svg/svg/trash-can-outline.svg'
import { useArrayFind, useUrlSearchParams } from '@vueuse/core'

import BaseDialog from '~resources/components/BaseDialog.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import ImageZoom from '~resources/components/ImageZoom.vue'
import LoadingSpinner from '~resources/components/LoadingSpinner.vue'
import { HotelResponse, useHotelAPI } from '~resources/lib/api/hotel/hotel'
import {
  HotelImageResponse,
  RoomImageResponse,
  useHotelImageRemoveAPI,
  useHotelImagesListAPI,
  useHotelImagesReorderAPI,
  useHotelImagesUploadAPI,
  useHotelRoomImageCreateAPI,
  useHotelRoomImageDeleteAPI,
  useHotelRoomImagesAPI,
} from '~resources/lib/api/hotel/images'
import { HotelRoomResponse, useHotelRoomAPI } from '~resources/lib/api/hotel/room'
import { showToast } from '~resources/lib/toast'
import { useUrlParams } from '~resources/lib/url-params'

import UploadDialog from './components/UploadDialog.vue'

const { hotel: hotelID } = useUrlParams()
const { room_id: roomID } = useUrlSearchParams<{ room_id?: number }>()

const {
  execute: fetchImages,
  data: imagesData,
  isFetching: isImagesFetching,
} = useHotelImagesListAPI({ hotelID })

const images = ref<HotelImageResponse[] | null>(null)

watch(imagesData, (value) => {
  images.value = value
})

const {
  execute: fetchRoomImages,
  data: roomImagesData,
  isFetching: isRoomImagesFetching,
} = useHotelRoomImagesAPI({ hotelID, roomID })

const roomImages = computed<RoomImageResponse[]>(() => {
  if (roomImagesData.value === null) return []
  return roomImagesData.value
})

const {
  execute: fetchHotel,
  data: hotelData,
  isFetching: isHotelFetching,
} = useHotelAPI({ hotelID })

const hotel = computed<HotelResponse | null>(() => hotelData.value)

const {
  execute: fetchRoom,
  data: roomData,
  isFetching: isRoomFetching,
} = useHotelRoomAPI({ hotelID, roomID })

const room = computed<HotelRoomResponse | null>(() => roomData.value)

const isUploadDialogOpened = ref(false)

const imagesToUpload = ref<File[] | null>(null)

const {
  execute: executeImagesUpload,
  isFetching: isImagesUploadFetching,
  data: imagesUploadData,
} = useHotelImagesUploadAPI(computed(() => ({
  hotelID,
  roomID,
  images: imagesToUpload.value,
})))

const uploadImages = () => {
  if (imagesToUpload.value === null) return
  executeImagesUpload()
}

watch(imagesUploadData, (value) => {
  if (value === null || !value.success) return
  fetchImages()
  fetchRoomImages()
  imagesToUpload.value = null
  isUploadDialogOpened.value = false
})

const imageIDToRemove = ref<number | null>(null)

const isRemoveImagePromptOpened = ref(false)

const imageToRemove = computed<HotelImageResponse | null>(() => {
  if (images.value === null) return null
  if (imageIDToRemove.value === null) return null
  const found = images.value.find(({ id }) => id === imageIDToRemove.value)
  if (found === undefined) return null
  return found
})

const {
  isFetching: isImageRemoveFetching,
  execute: executeRemoveImage,
  data: removeImageData,
} = useHotelImageRemoveAPI(computed(() => ({
  hotelID,
  imageID: imageIDToRemove.value,
})))

const removeImage = (imageID: number) => {
  imageIDToRemove.value = imageID
  isRemoveImagePromptOpened.value = true
}

const cancelRemoveImage = () => {
  imageIDToRemove.value = null
  isRemoveImagePromptOpened.value = false
}

watch(removeImageData, (value) => {
  if (value === null || !value.success) {
    showToast('Не удалось удалить фото')
    return
  }
  isRemoveImagePromptOpened.value = false
  if (imageToRemove.value === null) return
  showToast({
    title: 'Фото удалено',
    description: imageToRemove.value.file.name,
  })
  imageIDToRemove.value = null
  fetchImages()
})

const {
  execute: executeHotelImagesReorder,
  isFetching: isHotelImagesReorderFetching,
} = useHotelImagesReorderAPI(computed(() => ({
  hotelID,
  imagesIDs: images.value?.map(({ id }) => id) ?? null,
})))

const onDraggableUpdate = () => {
  nextTick(() => {
    executeHotelImagesReorder()
  })
}

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

const getRoomImage = (id: number): RoomImageResponse | undefined => {
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
  if (roomID && room.value) return room.value.custom_name
  if (hotel.value) return hotel.value.name
  return ''
})
</script>
<template>
  <BaseLayout :title="title" :loading="isHotelFetching">
    <template #header-controls>
      <BootstrapButton
        label="Добавить фотографии"
        :start-icon="plusIcon"
        severity="primary"
        @click="isUploadDialogOpened = true"
      />
    </template>
    <div v-if="isRoomFetching || isImagesFetching || isRoomImagesFetching">
      <LoadingSpinner class="loadingSpinner" />
    </div>
    <div v-else-if="images === null || images.length === 0">
      У этого отеля ещё нет фото. Загрузите их, нажав на кнопку выше.
    </div>
    <VueDraggable
      v-else
      v-model="images"
      class="images"
      handle="[data-draggable-handle]"
      animation="300"
      @update="onDraggableUpdate"
    >
      <div
        v-for="{ id, file: { url, name } } in images"
        :key="id"
        class="card"
        :class="{ hidden: isNeedHideImage(id) }"
      >
        <div class="pictureContainer">
          <BootstrapButton
            data-draggable-handle
            class="imageDragHandle"
            label="Потяните, чтобы изменить порядок фотографий"
            severity="dark"
            :only-icon="dragIcon"
            :loading="isHotelImagesReorderFetching"
          />
          <ImageZoom
            class="card-img-top picture"
            :src="url"
            :alt="name"
          />
        </div>
        <div class="card-body">
          <div class="actions">
            <div class="actionsStart">
              <template v-if="roomID">
                <BootstrapButton
                  v-if="!getRoomImage(id)"
                  severity="primary"
                  label="Привязать к номеру"
                  :start-icon="linkIcon"
                  @click="setImageToRoom(id)"
                />
                <BootstrapButton
                  v-else
                  label="Отвязать от номера"
                  :start-icon="linkOffIcon"
                  @click="deleteRoomImage(id)"
                />
              </template>
            </div>
            <div class="actionsEnd">
              <BootstrapButton
                v-if="!getRoomImage(id)"
                label="Удалить"
                severity="danger"
                :only-icon="trashIcon"
                :loading="isImageRemoveFetching && imageIDToRemove === id"
                @click="removeImage(id)"
              />
            </div>
          </div>
        </div>
      </div>
    </vuedraggable>
  </BaseLayout>
  <UploadDialog
    :opened="isUploadDialogOpened"
    :loading="isImagesUploadFetching"
    :files="imagesToUpload"
    @files="(files) => imagesToUpload = files"
    @upload="() => uploadImages()"
    @close="() => isUploadDialogOpened = false"
  />
  <BaseDialog
    :opened="isRemoveImagePromptOpened"
    :disabled="isImageRemoveFetching"
  >
    <template #title>Действительно удалить это фото?</template>
    <ImageZoom
      v-if="imageToRemove !== null"
      class="picture"
      :src="imageToRemove.file.url"
      :alt="imageToRemove.file.name"
      :disabled="isImageRemoveFetching"
    />
    <template #actions-end>
      <BootstrapButton
        label="Удалить"
        variant="outline"
        severity="danger"
        :loading="isImageRemoveFetching"
        :start-icon="trashIcon"
        @click="executeRemoveImage"
      />
      <BootstrapButton
        label="Отмена"
        severity="light"
        :disabled="isImageRemoveFetching"
        @click="cancelRemoveImage"
      />
    </template>
  </BaseDialog>
</template>
<style lang="scss" scoped>
.loadingSpinner {
  font-size: 1.5em;
}

.images {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1em;
}

.pictureContainer {
  position: relative;
  flex-grow: 1;
}

.picture {
  display: block;
  object-fit: cover;
  object-position: center;
  width: 100%;
  aspect-ratio: 4/3;
  backdrop-filter: blur(10px);
}

.imageDragHandle {
  --position: 0.5em;

  position: absolute;
  top: var(--position);
  left: var(--position);
  z-index: 1;
  cursor: grab;
}

.actions {
  display: flex;
}

.actionsStart {
  flex-grow: 1;
}

.actionsEnd {
  flex-shrink: 0;
}

.upload {
  display: flex;
  flex-flow: column;
  gap: 1em;
}

.sortable-chosen {
  opacity: 0.3;
}
</style>
