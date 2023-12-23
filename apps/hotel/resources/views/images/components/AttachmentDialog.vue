<script lang="ts" setup>
import { computed, ref, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { AttachmentDialogImageProp } from '~resources/views/images/components/lib'

import { HotelRoomID, HotelRoomImage } from '~api/hotel'
import { HotelID } from '~api/hotel/get'
import { useHotelRoomAttachImageAPI, useHotelRoomDetachImageAPI, useHotelSetMainImageAPI, useHotelUnSetMainImageAPI } from '~api/hotel/images/update'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import BootstrapCheckbox from '~components/Bootstrap/BootstrapCheckbox.vue'

const props = withDefaults(defineProps<{
  image: AttachmentDialogImageProp
  hotel: HotelID
  rooms: HotelRoomImage[]
  isRoomsFetching?: MaybeRef<boolean>
  opened: boolean
}>(), {
  isRoomsFetching: false,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'update-attached-image-rooms'): void
  (event: 'update-set-main-image-hotel'): void
}>()

const roomIDToChangeImageAttachment = ref<HotelRoomID | null>(null)

const hotelRoomChangeImageAttachmentPayload = computed(() =>
  (roomIDToChangeImageAttachment.value === null ? null : ({
    hotelID: props.hotel,
    roomID: roomIDToChangeImageAttachment.value,
    imageID: props.image.id,
  })))

const hotelSetMainImagePayload = computed(() =>
  ({
    hotelID: props.hotel,
    imageID: props.image.id,
  }))

const {
  execute: executeAttach,
  data: attachData,
  isFetching: isAttachFetching,
} = useHotelRoomAttachImageAPI(hotelRoomChangeImageAttachmentPayload)

const {
  execute: executeDetach,
  data: detachData,
  isFetching: isDetachFetching,
} = useHotelRoomDetachImageAPI(hotelRoomChangeImageAttachmentPayload)

const {
  execute: executeSetMainImage,
  data: setMainImageData,
  isFetching: isSetMainImageFetching,
} = useHotelSetMainImageAPI(hotelSetMainImagePayload)

const {
  execute: executeUnSetMainImage,
  data: unSetMainImageData,
  isFetching: isUnSetMainImageFetching,
} = useHotelUnSetMainImageAPI(hotelSetMainImagePayload)

const changeImageAttachment = async (roomID: HotelRoomID, value: boolean) => {
  // TODO revert checkbox state to previous if request is not success
  if (value) {
    roomIDToChangeImageAttachment.value = roomID
    await executeAttach()
    if (attachData.value !== null && attachData.value.success) {
      roomIDToChangeImageAttachment.value = null
      emit('update-attached-image-rooms')
    }
  } else {
    roomIDToChangeImageAttachment.value = roomID
    await executeDetach()
    if (detachData.value !== null && detachData.value.success) {
      roomIDToChangeImageAttachment.value = null
      emit('update-attached-image-rooms')
    }
  }
}

const changeSetMainImageToHotel = async (value: boolean) => {
  // TODO revert checkbox state to previous if request is not success
  if (value) {
    await executeSetMainImage()
    if (setMainImageData.value !== null && setMainImageData.value.success) {
      emit('update-set-main-image-hotel')
    }
  } else {
    await executeUnSetMainImage()
    if (unSetMainImageData.value !== null && unSetMainImageData.value.success) {
      emit('update-set-main-image-hotel')
    }
  }
}

const isChangeImageAttachmentInProgress = computed<boolean>(() => (
  isAttachFetching.value || isDetachFetching.value || unref(props.isRoomsFetching)
  || isSetMainImageFetching.value || isUnSetMainImageFetching.value
))

const isCheckboxDisabled = computed<boolean>(() =>
  props.rooms.length === 0 || isChangeImageAttachmentInProgress.value)
</script>
<template>
  <BaseDialog
    :opened="opened"
    :disabled="isChangeImageAttachmentInProgress"
    class="attachmentDialog"
    @close="emit('close')"
  >
    <div v-if="opened" class="body">
      <div class="bodyForm">
        <div class="formTitle h6">Укажите, изображён ли здесь номер:</div>
        <ul class="roomsList list-group list-group-flush">
          <li
            v-for="room in rooms"
            :key="room.id"
            class="list-group-item"
          >
            <BootstrapCheckbox
              :label="room.name"
              :value="room.isImageLinked"
              :disabled="isCheckboxDisabled"
              @input="value => changeImageAttachment(room.id, value)"
            />
          </li>
          <li class="list-group-item gts-list-group-item__border-top--bold">
            <BootstrapCheckbox
              label="На главную отеля"
              :value="image.isMain"
              :disabled="isChangeImageAttachmentInProgress"
              @input="value => changeSetMainImageToHotel(value)"
            />
          </li>
        </ul>
      </div>
      <div class="bodyImage">
        <BootstrapButton
          class="closeButton"
          label="Закрыть (Esc)"
          only-icon="close"
          size="small"
          severity="dark"
          variant="filled"
          :disabled="isChangeImageAttachmentInProgress"
          @click="() => emit('close')"
        />
        <div class="pictureContainer" :style="`--src: url('${image.src}')`">
          <img
            class="picture"
            :src="image.src"
            :alt="image.alt"
          />
        </div>
      </div>
    </div>
  </BaseDialog>
</template>
<style>
:root .attachmentDialog {
  --dialog-width: 60em;
  --part-padding: 0;
  --body-border: 0;
}
</style>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.body {
  display: flex;
  min-height: 30em;
}

.bodyForm {
  flex-shrink: 0;
  max-width: 15em;
}

.formTitle {
  padding: bs.$modal-inner-padding;
  padding-bottom: unset;
}

.bodyImage {
  position: relative;
  flex-grow: 1;
}

.pictureContainer {
  position: relative;
  overflow: hidden;
  height: 100%;
  border-top-right-radius: bs.$modal-content-border-radius;
  border-bottom-right-radius: bs.$modal-content-border-radius;
  background-image: var(--src);
  background-position: center;
  background-size: cover;
}

.picture {
  position: absolute;
  inset: 0;
  object-fit: contain;
  object-position: center;
  width: 100%;
  height: 100%;
  backdrop-filter: blur(10px);
}

.closeButton {
  --position: 1em;

  position: absolute;
  top: var(--position);
  right: var(--position);
  z-index: 2;

  :root & {
    color: white;
  }
}

.roomsList {
  border-bottom-left-radius: bs.$modal-content-border-radius;
}

.gts-list-group-item__border-top--bold {
  border-top: 2px solid #000;
}
</style>
