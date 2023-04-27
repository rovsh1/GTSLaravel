<script setup lang="ts">
import { computed, ref, watch } from 'vue'

import linkIcon from '@mdi/svg/svg/link.svg'
import linkOffIcon from '@mdi/svg/svg/link-off.svg'
import plusIcon from '@mdi/svg/svg/plus.svg'
import trashIcon from '@mdi/svg/svg/trash-can-outline.svg'
import { useArrayFind, useUrlSearchParams } from '@vueuse/core'

import BaseButton from '~resources/components/BaseButton.vue'
import BaseDialog from '~resources/components/BaseDialog.vue'
import BaseLayout from '~resources/components/BaseLayout.vue'
import ImageZoom from '~resources/components/ImageZoom.vue'
import LoadingSpinner from '~resources/components/LoadingSpinner.vue'
import {
  useHotelAPI, useHotelImageRemoveAPI,
  useHotelImagesListAPI, useHotelImagesUploadAPI,
  useHotelRoomAPI,
  useHotelRoomImageCreateAPI, useHotelRoomImageDeleteAPI, useHotelRoomImagesAPI,
} from '~resources/lib/api/hotel'
import { Hotel, Room } from '~resources/lib/models'
import { showToast } from '~resources/lib/toast'
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

const imageIDToRemove = ref<number | null>(null)

const imageRemovePayload = computed(() => ({ hotelID, imageID: imageIDToRemove.value }))

const isRemoveImagePromptOpened = ref(false)

const imageToRemove = computed<HotelImage | null>(() => {
  if (images.value === null) return null
  if (imageIDToRemove.value === null) return null
  const found = images.value.find(({ id }) => id === imageIDToRemove.value)
  if (found === undefined) return null
  return found
})

const {
  isFetching: isImageRemoveFetching,
  execute: executeRemoveImage,
  onFetchFinally: onRemoveImageFinally,
  data: removeImageData,
} = useHotelImageRemoveAPI(imageRemovePayload)

const removeImage = (imageID: number) => {
  imageIDToRemove.value = imageID
  isRemoveImagePromptOpened.value = true
}

const cancelRemoveImage = () => {
  imageIDToRemove.value = null
  isRemoveImagePromptOpened.value = false
}

watch(removeImageData, (value) => {
  if (value && value.success && imageToRemove.value !== null) {
    showToast({
      title: 'Фото удалено',
      description: imageToRemove.value.file.name,
    })
    fetchImages()
  } else {
    showToast('Не удалось удалить фото')
  }
})

onRemoveImageFinally(() => {
  isRemoveImagePromptOpened.value = false
  imageIDToRemove.value = null
})

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
  <BaseLayout :title="title" :loading="isHotelFetching as boolean">
    <template #header-controls>
      <BaseButton
        label="Добавить фотографии"
        :start-icon="plusIcon"
        severity="primary"
        @click="isUploadDialogOpened = true"
      />
    </template>

    <div v-if="isRoomFetching || isImagesFetching || isRoomImagesFetching">
      <LoadingSpinner class="loadingSpinner" />
    </div>
    <div v-else-if="images === null">
      No images
    </div>
    <div v-else class="images">
      <div
        v-for="{ id, file: { url, name } } in images"
        :key="id"
        class="image"
        :class="{ hidden: isNeedHideImage(id) }"
      >
        <ImageZoom
          class="picture"
          :src="url"
          :alt="name"
        />
        <div class="actions">
          <div class="actionsStart">
            <template v-if="roomID">
              <BaseButton
                v-if="!getRoomImage(id)"
                severity="primary"
                label="Привязать к номеру"
                :start-icon="linkIcon"
                @click="setImageToRoom(id)"
              />
              <BaseButton
                v-else
                label="Отвязать от номера"
                :start-icon="linkOffIcon"
                @click="deleteRoomImage(id)"
              />
            </template>
          </div>
          <div class="actionsEnd">
            <BaseButton
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
  </BaseLayout>

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
  <BaseDialog
    :opened="isRemoveImagePromptOpened"
    :disabled="isImageRemoveFetching as boolean"
  >
    <template #title>Действительно удалить это фото?</template>
    <ImageZoom
      v-if="imageToRemove !== null"
      class="picture"
      :src="imageToRemove.file.url"
      :alt="imageToRemove.file.name"
    />
    <template #actions>
      <BaseButton
        label="Удалить"
        severity="danger"
        :loading="isImageRemoveFetching as boolean"
        @click="executeRemoveImage"
      />
      <BaseButton
        label="Отмена"
        :disabled="isImageRemoveFetching as boolean"
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
