<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'
import InlineSVG from 'vue-inline-svg'

import dragIcon from '@mdi/svg/svg/drag.svg'
import linkIcon from '@mdi/svg/svg/link.svg'
import linkOffIcon from '@mdi/svg/svg/link-off.svg'
import plusIcon from '@mdi/svg/svg/plus.svg'
import trashIcon from '@mdi/svg/svg/trash-can-outline.svg'
import { useUrlSearchParams } from '@vueuse/core'
import { z } from 'zod'

import { HotelResponse, useHotelAPI } from '~api/hotel/get'
import { HotelImageResponse } from '~api/hotel/images'
import { RoomImageResponse, useHotelImagesListAPI, useHotelRoomImagesAPI } from '~api/hotel/images/list'
import { useHotelImageRemoveAPI } from '~api/hotel/images/remove'
import { useHotelImagesReorderAPI } from '~api/hotel/images/reorder'
import { useHotelRoomImageCreateAPI, useHotelRoomImageDeleteAPI } from '~api/hotel/images/update'
import { useHotelImagesUploadAPI } from '~api/hotel/images/upload'
import { HotelRoomResponse, useHotelRoomAPI } from '~api/hotel/room'

import { injectInitialData } from '~lib/vue'

import BaseDialog from '~components/BaseDialog.vue'
import BaseLayout from '~components/BaseLayout.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import ImageZoom from '~components/ImageZoom.vue'
import LoadingSpinner from '~components/LoadingSpinner.vue'

import UploadDialog from './components/UploadDialog.vue'

const { hotelID } = injectInitialData(z.object({
  hotelID: z.number(),
}))

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

const hotelRoomImagesProps = computed(() =>
  (roomID === undefined ? null : { hotelID, roomID }))

const {
  execute: fetchRoomImages,
  data: roomImagesData,
  isFetching: isRoomImagesFetching,
} = useHotelRoomImagesAPI(hotelRoomImagesProps)

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

const hotelRoomProps = computed(() =>
  (roomID === undefined
    ? null
    : { hotelID, roomID }))

const {
  execute: fetchRoom,
  data: roomData,
  isFetching: isRoomFetching,
} = useHotelRoomAPI(hotelRoomProps)

const room = computed<HotelRoomResponse | null>(() => roomData.value)

const isUploadDialogOpened = ref(false)

const imagesToUpload = ref<File[] | null>(null)

const hotelImagesUploadProps = computed(() =>
  (imagesToUpload.value === null
    ? null
    : {
      hotelID,
      roomID,
      images: imagesToUpload.value,
    }))

const {
  execute: executeImagesUpload,
  isFetching: isImagesUploadFetching,
  data: imagesUploadData,
} = useHotelImagesUploadAPI(hotelImagesUploadProps)

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

const hotelImageRemoveProps = computed(() =>
  (imageIDToRemove.value === null
    ? null
    : {
      hotelID,
      imageID: imageIDToRemove.value,
    }))

const {
  isFetching: isImageRemoveFetching,
  execute: executeRemoveImage,
  data: removeImageData,
} = useHotelImageRemoveAPI(hotelImageRemoveProps)

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
    showToast({ title: 'Не удалось удалить фото' })
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

const hotelImagesReorderProps = computed(() =>
  (images.value === null ? null : {
    hotelID,
    imagesIDs: images.value.map(({ id }) => id),
  }))

const {
  execute: executeHotelImagesReorder,
  isFetching: isHotelImagesReorderFetching,
} = useHotelImagesReorderAPI(hotelImagesReorderProps)

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

const isImageAttachedToRoom = (id: number): boolean =>
  roomImages.value.find((image) => image.image_id === id) !== undefined

const isImageVisible = (id: number): boolean => {
  if (roomID === undefined) return true

  return isImageAttachedToRoom(id) !== undefined
}

fetchRoom()
fetchRoomImages()
fetchHotel()
fetchImages()

const title = computed<string>(() => {
  if (hotel.value === null) return ''
  const { name: hotelLabel } = hotel.value
  if (roomID && room.value) {
    const { custom_name: roomLabel } = room.value
    return `${hotelLabel} — ${roomLabel}`
  }
  return hotelLabel
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
        :class="{ hidden: !isImageVisible(id) }"
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
          <div v-if="roomID !== undefined && !isImageAttachedToRoom(id)" class="pictureOverlay">
            <InlineSVG :src="linkOffIcon" class="pictureOverlayIcon" />
            Фото не привязано к номеру
          </div>
          <ImageZoom
            class="card-img-top picture"
            :class="{ isNotAttachedToRoom: roomID !== undefined && !isImageAttachedToRoom(id) }"
            :src="url"
            :alt="name"
          />
        </div>
        <div class="card-body">
          <div class="actions">
            <div class="actionsStart">
              <template v-if="roomID">
                <BootstrapButton
                  v-if="!isImageAttachedToRoom(id)"
                  severity="primary"
                  variant="outline"
                  label="Привязать к номеру"
                  :start-icon="linkIcon"
                  @click="setImageToRoom(id)"
                />
                <BootstrapButton
                  v-else
                  severity="warning"
                  label="Отвязать от номера"
                  :start-icon="linkOffIcon"
                  @click="deleteRoomImage(id)"
                />
              </template>
            </div>
            <div class="actionsEnd">
              <BootstrapButton
                v-if="roomID === undefined || !isImageAttachedToRoom(id)"
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

.pictureOverlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-flow: column;
  justify-content: center;
  align-items: center;
}

.pictureOverlayIcon {
  width: 50%;
}

.picture {
  display: block;
  object-fit: cover;
  object-position: center;
  width: 100%;
  aspect-ratio: 4/3;

  &.isNotAttachedToRoom {
    &:not(.medium-zoom-image--opened) {
      opacity: 0.3;
    }
  }
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
