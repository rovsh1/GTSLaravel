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

import AttachmentDialog from '~resources/views/hotel/images/components/AttachmentDialog.vue'
import { AttachmentDialogImageProp, isImageAttachedToRoom } from '~resources/views/hotel/images/components/lib'

import { HotelID, HotelResponse, useHotelAPI } from '~api/hotel/get'
import { FileResponse, HotelImage, HotelImageID } from '~api/hotel/images'
import {
  UseHotelImages,
  useHotelImagesListAPI,
  useHotelRoomImagesAPI,
} from '~api/hotel/images/list'
import { useHotelImageRemoveAPI } from '~api/hotel/images/remove'
import { useHotelImagesReorderAPI } from '~api/hotel/images/reorder'
import { useHotelRoomAttachImageAPI, useHotelRoomDetachImageAPI } from '~api/hotel/images/update'
import { useHotelImagesUploadAPI } from '~api/hotel/images/upload'
import { HotelRoom, useHotelRoomAPI } from '~api/hotel/room'
import { UseHotelRooms, useHotelRoomsListAPI } from '~api/hotel/rooms'

import { injectInitialData } from '~lib/vue'

import BaseDialog from '~components/BaseDialog.vue'
import BaseLayout from '~components/BaseLayout.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import ImageZoom from '~components/ImageZoom.vue'
import LoadingSpinner from '~components/LoadingSpinner.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

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

const images = ref<UseHotelImages>(null)

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

const roomImages = computed<UseHotelImages>(() => roomImagesData.value)

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

const room = computed<HotelRoom | null>(() => roomData.value)

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

const {
  execute: executeHotelImagesReorder,
  isFetching: isHotelImagesReorderFetching,
} = useHotelImagesReorderAPI(hotelImagesReorderProps)

const handleDraggableUpdate = () => {
  nextTick(() => {
    executeHotelImagesReorder()
  })
}

const attachImageToRoom = async (imageID: number) => {
  if (roomID === undefined) return
  await useHotelRoomAttachImageAPI({ hotelID, roomID, imageID })
    .execute()
  await fetchRoomImages()
  await fetchImages()
}

const deleteRoomImage = async (imageID: number) => {
  if (roomID === undefined) return
  await useHotelRoomDetachImageAPI({ hotelID, roomID, imageID })
    .execute()
  await fetchRoomImages()
  await fetchImages()
}

const isImageVisible = (id: HotelImageID): boolean => {
  if (roomID === undefined || room.value === null) return true
  return isImageAttachedToRoom(id, room.value) !== undefined
}

const {
  data: hotelRoomsData,
  execute: fetchHotelRooms,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI({ hotelID })

const hotelRooms = computed<UseHotelRooms>(() => hotelRoomsData.value)

fetchRoom()
fetchRoomImages()
fetchHotel()
fetchImages()

if (roomID === undefined) {
  fetchHotelRooms()
}

const attachmentDialogImage = ref<AttachmentDialogImageProp | null>(null)

const isAttachmentDialogOpened = ref(false)

type OpenAttachmentDialogProps = { id: HotelImageID } & Pick<FileResponse, 'url' | 'name'>
const openAttachmentDialog = ({ id, url, name }: OpenAttachmentDialogProps) => {
  isAttachmentDialogOpened.value = true
  attachmentDialogImage.value = { id, src: url, alt: name }
}

const closeAttachmentDialog = () => {
  isAttachmentDialogOpened.value = false
}

const title = computed<string>(() => {
  if (hotel.value === null) return ''
  const { name: hotelLabel } = hotel.value
  if (roomID && room.value) {
    const { customName } = room.value
    return `${hotelLabel} — ${customName}`
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
    <div v-if="isInitialFetching">
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
      @update="handleDraggableUpdate"
    >
      <OverlayLoading v-if="isRefetching" />
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
          <div
            v-if="
              roomID !== undefined
                && room !== null
                && !isImageAttachedToRoom(id, room)
            "
            class="pictureOverlay"
          >
            <InlineSVG :src="linkOffIcon" class="pictureOverlayIcon" />
            Фото не привязано к номеру
          </div>
          <ImageZoom
            class="card-img-top picture"
            :class="{
              isNotAttachedToRoom:
                roomID !== undefined
                && room !== null
                && !isImageAttachedToRoom(id, room),
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
                  v-if="room !== null && !isImageAttachedToRoom(id, room)"
                  severity="primary"
                  variant="outline"
                  label="Привязать к номеру"
                  :start-icon="linkIcon"
                  @click="attachImageToRoom(id)"
                />
                <BootstrapButton
                  v-else
                  severity="warning"
                  label="Отвязать от номера"
                  :start-icon="linkOffIcon"
                  @click="deleteRoomImage(id)"
                />
              </template>
              <template v-else>
                <BootstrapButton
                  severity="primary"
                  variant="outline"
                  label="Привязать к номерам"
                  :start-icon="linkIcon"
                  @click="openAttachmentDialog({ id, url, name })"
                  @mouseover="attachmentDialogImage = { id, src: url, alt: name }"
                  @focusin="attachmentDialogImage = { id, src: url, alt: name }"
                />
              </template>
            </div>
            <div class="actionsEnd">
              <BootstrapButton
                v-if="
                  roomID === undefined
                    || (
                      room !== null
                      && !isImageAttachedToRoom(id, room)
                    )
                "
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
  <AttachmentDialog
    v-if="attachmentDialogImage !== null && hotelRooms !== null"
    :image="attachmentDialogImage"
    :rooms="hotelRooms"
    :is-rooms-fetching="isHotelRoomsFetching"
    :hotel="hotelID as HotelID"
    :opened="isAttachmentDialogOpened"
    @close="closeAttachmentDialog"
    @update-rooms="fetchHotelRooms"
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
