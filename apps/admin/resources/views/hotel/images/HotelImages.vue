<script setup lang="ts">
import { computed, nextTick, ref, watch, watchEffect } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'

import { useUrlSearchParams } from '@vueuse/core'
import { z } from 'zod'

import AttachmentDialog from '~resources/views/hotel/images/components/AttachmentDialog.vue'
import { AttachmentDialogImageProp, isImageAttachedToRoom, UploadStatus } from '~resources/views/hotel/images/components/lib'

import { HotelID, HotelResponse, useHotelGetAPI } from '~api/hotel/get'
import { FileResponse, HotelImage, HotelImageID } from '~api/hotel/images'
import {
  UseHotelImages,
  useHotelImagesListAPI,
  UseHotelRoomImages,
  useHotelRoomImagesAPI,
} from '~api/hotel/images/list'
import { useHotelImageRemoveAPI } from '~api/hotel/images/remove'
import { useHotelImagesReorderAPI, useHotelRoomImagesReorderAPI } from '~api/hotel/images/reorder'
import { useHotelRoomAttachImageAPI, useHotelRoomDetachImageAPI } from '~api/hotel/images/update'
import { useHotelImagesUploadAPI } from '~api/hotel/images/upload'
import { HotelRoom, useHotelRoomAPI } from '~api/hotel/room'
import { useHotelRoomsListWithAttachedImageAPI } from '~api/hotel/rooms-image'

import BaseDialog from '~components/BaseDialog.vue'
import BaseLayout from '~components/BaseLayout.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import ImageZoom from '~components/ImageZoom.vue'
import InlineIcon from '~components/InlineIcon.vue'
import LoadingSpinner from '~components/LoadingSpinner.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

import { createHotelSwitcher } from '~widgets/hotel-switcher/hotel-switcher'

import { requestInitialData } from '~helpers/initial-data'

import UploadDialog from './components/UploadDialog.vue'

const { hotelID } = requestInitialData(z.object({
  hotelID: z.number(),
}))

const { room_id: roomID } = useUrlSearchParams<{ room_id?: number }>()

const {
  execute: fetchImages,
  data: imagesData,
  isFetching: isImagesFetching,
} = useHotelImagesListAPI({ hotelID })

const images = ref<UseHotelImages>([])

const hotelRoomImagesProps = computed(() =>
  (roomID === undefined ? null : { hotelID, roomID }))

const {
  execute: fetchRoomImages,
  data: roomImagesData,
  isFetching: isRoomImagesFetching,
} = useHotelRoomImagesAPI(hotelRoomImagesProps)

const loadAllImages = async () => {
  await Promise.all([
    fetchRoomImages(),
    fetchImages(),
  ])
}

const roomImages = computed<UseHotelRoomImages>(() => roomImagesData.value || [])

const {
  execute: fetchHotel,
  data: hotelData,
  isFetching: isHotelFetching,
} = useHotelGetAPI({ hotelID })

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

const room = computed<HotelRoom | null>(() => roomData.value)

const isUploadDialogOpened = ref(false)

const isImagesUploadFetching = ref(false)

const imagesToUpload = ref<File[] | null>(null)

const uploadsImageStatus = ref<UploadStatus[] | null>(null)

const uploadImages = async () => {
  if (imagesToUpload.value === null) return
  isImagesUploadFetching.value = true
  const uploadPromises = imagesToUpload.value.map(async (image) => {
    const { data, execute } = useHotelImagesUploadAPI({
      hotelID,
      roomID,
      image,
    })
    await execute()
    return {
      status: data.value?.success || false,
      name: image.name,
    }
  })

  const results = await Promise.all(uploadPromises)

  uploadsImageStatus.value = results

  const wrongStatusUploadImage = uploadsImageStatus.value.filter((upload) => !upload.status)

  loadAllImages()
  if (wrongStatusUploadImage.length > 0) {
    isImagesUploadFetching.value = false
  } else {
    isUploadDialogOpened.value = false
    isImagesUploadFetching.value = false
    imagesToUpload.value = null
  }
}

const imageIDToRemove = ref<number | null>(null)

const isRemoveImagePromptOpened = ref(false)

const imageToRemove = computed<HotelImage | null>(() => {
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

const hotelRoomImagesReorderProps = computed(() =>
  (images.value === null || !roomID ? null : {
    hotelID,
    roomID,
    imagesIDs: images.value.map(({ id }) => id),
  }))

const {
  execute: executeHotelImagesReorder,
  isFetching: isHotelImagesReorderFetching,
} = useHotelImagesReorderAPI(hotelImagesReorderProps)

const {
  execute: executeHotelRoomImagesReorder,
  isFetching: isHotelRoomImagesReorderFetching,
} = useHotelRoomImagesReorderAPI(hotelRoomImagesReorderProps)

const attachImageToRoom = async (imageID: number) => {
  if (roomID === undefined) return
  await useHotelRoomAttachImageAPI({ hotelID, roomID, imageID })
    .execute()
  await loadAllImages()
}

const deleteRoomImage = async (imageID: number) => {
  if (roomID === undefined) return
  await useHotelRoomDetachImageAPI({ hotelID, roomID, imageID })
    .execute()
  await loadAllImages()
}

const isImageVisible = (id: HotelImageID): boolean => {
  if (roomID === undefined || room.value === null) return true
  return isImageAttachedToRoom(id, roomImages.value) !== undefined
}

const attachmentDialogImage = ref<AttachmentDialogImageProp | null>(null)

const hotelRoomsWithAttachedImageProps = computed(() =>
  (attachmentDialogImage.value === null ? null : {
    hotelID,
    imageID: attachmentDialogImage.value.id,
  }))

const isAttachmentDialogOpened = ref(false)

const {
  data: hotelRoomsWithAttachedImage,
  execute: fetchHotelRoomsWithAttachedImage,
  isFetching: isHotelRoomsWithAttachedImageFetching,
} = useHotelRoomsListWithAttachedImageAPI(hotelRoomsWithAttachedImageProps)

watch([isAttachmentDialogOpened, hotelRoomsWithAttachedImageProps], (value) => {
  if (value[0]) {
    fetchHotelRoomsWithAttachedImage()
  }
})

fetchRoom()
fetchHotel()
loadAllImages()

type OpenAttachmentDialogProps = { id: HotelImageID; isMain: boolean } & Pick<FileResponse, 'url' | 'name'>
const openAttachmentDialog = async ({ id, url, name, isMain }: OpenAttachmentDialogProps) => {
  attachmentDialogImage.value = { id, src: url, alt: name, isMain }
  isAttachmentDialogOpened.value = true
}

const closeAttachmentDialog = () => {
  isAttachmentDialogOpened.value = false
}

const title = computed<string>(() => {
  if (hotel.value === null) return ''
  const { name: hotelLabel } = hotel.value
  if (roomID && room.value) {
    const { name } = room.value
    return `${hotelLabel} — ${name}`
  }
  return hotelLabel
})

const isInitialFetching = computed(() => (
  (room.value === null && isRoomFetching.value)
  || ((images.value === null || images.value.length === 0) && isImagesFetching.value)
  || ((roomImages.value === null || roomImages.value.length === 0) && isRoomImagesFetching.value)
))

const isRefetching = computed(() => (
  isRoomFetching.value || isImagesFetching.value || isRoomImagesFetching.value
))

const reorderRoomImages = async () => {
  if (roomID) {
    const attachedImages = images.value?.filter((image) => isImageAttachedToRoom(image.id, roomImages.value)) as UseHotelImages
    const orderedAttachedImages = [] as UseHotelImages
    roomImages.value.forEach((roomImage) => {
      const findedImage = attachedImages?.find((attachedImage) => attachedImage.id === roomImage.id)
      if (findedImage) {
        orderedAttachedImages.push(findedImage)
      }
    })
    const unAttachedImages = images.value?.filter((image) => !isImageAttachedToRoom(image.id, roomImages.value)) as UseHotelImages
    images.value = orderedAttachedImages?.concat(unAttachedImages || []) as UseHotelImages
  }
}

const updateHotelRoomImagesOrder = async () => {
  if (roomID) {
    await executeHotelRoomImagesReorder()
  } else {
    await executeHotelImagesReorder()
  }
  loadAllImages()
}

watch([imagesData, roomImages], (value) => {
  images.value = value[0] ? [...value[0]] : []
  reorderRoomImages()
})

watchEffect(() => {
  if (!isHotelFetching.value) {
    nextTick(() => {
      createHotelSwitcher(document.getElementsByClassName('content-header')[0], false)
    })
  }
})

</script>
<template>
  <BaseLayout :title="title" :loading="isHotelFetching">
    <template #header-controls>
      <BootstrapButton
        label="Добавить фотографии"
        start-icon="add"
        severity="primary"
        @click="isUploadDialogOpened = true"
      />
    </template>
    <div v-if="isInitialFetching">
      <LoadingSpinner class="loadingSpinner" />
    </div>
    <div v-else-if="images === null || images.length === 0">
      У этого отеля ещё нет фото. Загрузите их, нажав на кнопку выше.
    </div>
    <div v-else>
      <VueDraggable
        v-model="images"
        class="images"
        handle="[data-draggable-handle]"
        animation="300"
        @update="updateHotelRoomImagesOrder"
      >
        <OverlayLoading v-if="isRefetching" />
        <div
          v-for="{ id, isMain, file: { url, name } } in images"
          :key="id"
          class="card"
          :class="{ hidden: !isImageVisible(id) }"
        >
          <div class="pictureContainer">
            <BootstrapButton
              v-if="(roomID !== undefined
                && room !== null
                && isImageAttachedToRoom(id, roomImages)) || (roomID == undefined || room == null)
              "
              data-draggable-handle
              class="imageDragHandle"
              label="Потяните, чтобы изменить порядок фотографий"
              severity="dark"
              only-icon="drag_indicator"
              :loading="isHotelImagesReorderFetching || isHotelRoomImagesReorderFetching"
            />
            <div
              v-if="roomID !== undefined
                && room !== null
                && !isImageAttachedToRoom(id, roomImages)
              "
              class="pictureOverlay"
            >
              <InlineIcon icon="link_off" class="pictureOverlayIcon" />
              Фото не привязано к номеру
            </div>
            <ImageZoom
              class="card-img-top picture"
              :class="{
                isNotAttachedToRoom:
                  roomID !== undefined
                  && room !== null
                  && !isImageAttachedToRoom(id, roomImages),
              }"
              :src="url"
              :alt="name"
            />
          </div>
          <div class="card-body">
            <div class="actions">
              <div class="actionsStart">
                <template v-if="roomID">
                  <BootstrapButton
                    v-if="room !== null && !isImageAttachedToRoom(id, roomImages)"
                    severity="primary"
                    variant="outline"
                    label="Привязать к номеру"
                    start-icon="link"
                    @click="attachImageToRoom(id)"
                  />
                  <BootstrapButton
                    v-else
                    severity="warning"
                    label="Отвязать от номера"
                    start-icon="link_off"
                    @click="deleteRoomImage(id)"
                  />
                </template>
                <template v-else>
                  <BootstrapButton
                    severity="primary"
                    variant="outline"
                    label="Привязать к номерам"
                    start-icon="link"
                    @click="openAttachmentDialog({ id, url, name, isMain: !!isMain })"
                    @mouseover="attachmentDialogImage = { id, src: url, alt: name, isMain: !!isMain }"
                    @focusin="attachmentDialogImage = { id, src: url, alt: name, isMain: !!isMain }"
                  />
                </template>
              </div>
              <div class="actionsEnd">
                <BootstrapButton
                  v-if="roomID === undefined
                    || (
                      room !== null
                      && !isImageAttachedToRoom(id, roomImages)
                    )
                  "
                  label="Удалить"
                  severity="danger"
                  only-icon="delete"
                  :loading="isImageRemoveFetching && imageIDToRemove === id"
                  @click="removeImage(id)"
                />
              </div>
            </div>
          </div>
        </div>
      </vuedraggable>
    </div>
  </BaseLayout>
  <UploadDialog
    :opened="isUploadDialogOpened"
    :loading="isImagesUploadFetching"
    :files="imagesToUpload"
    :upload-status="uploadsImageStatus"
    @files="(files) => imagesToUpload = files"
    @upload="() => uploadImages()"
    @close="() => isUploadDialogOpened = false"
  />
  <BaseDialog :opened="isRemoveImagePromptOpened" :disabled="isImageRemoveFetching">
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
        start-icon="delete"
        @click="executeRemoveImage"
      />
      <BootstrapButton label="Отмена" severity="light" :disabled="isImageRemoveFetching" @click="cancelRemoveImage" />
    </template>
  </BaseDialog>
  <AttachmentDialog
    v-if="attachmentDialogImage !== null && hotelRoomsWithAttachedImage !== null"
    :image="attachmentDialogImage"
    :rooms="hotelRoomsWithAttachedImage"
    :is-rooms-fetching="isHotelRoomsWithAttachedImageFetching"
    :hotel="hotelID as HotelID"
    :opened="isAttachmentDialogOpened"
    @update-set-main-image-hotel="fetchImages"
    @update-attached-image-rooms="fetchHotelRoomsWithAttachedImage"
    @close="closeAttachmentDialog"
  />
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
  align-items: center;
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
