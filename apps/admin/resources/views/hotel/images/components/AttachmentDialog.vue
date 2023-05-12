<script lang="ts" setup>
import { computed, ref, unref } from 'vue'

import closeIcon from '@mdi/svg/svg/close.svg'
import { MaybeRef } from '@vueuse/core'

import { AttachmentDialogImageProp, isImageAttachedToRoom } from '~resources/views/hotel/images/components/lib'

import { HotelRoomID } from '~api/hotel'
import { HotelID } from '~api/hotel/get'
import { useHotelRoomAttachImageAPI, useHotelRoomDetachImageAPI } from '~api/hotel/images/update'
import { HotelRoom } from '~api/hotel/room'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import BootstrapCheckbox from '~components/Bootstrap/BootstrapCheckbox.vue'

const props = withDefaults(defineProps<{
  image: AttachmentDialogImageProp
  hotel: HotelID
  rooms: HotelRoom[]
  isRoomsFetching?: MaybeRef<boolean>
  opened: boolean
}>(), {
  isRoomsFetching: false,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'update-rooms'): void
}>()

const roomIDToChangeImageAttachment = ref<HotelRoomID | null>(null)

const hotelRoomChangeImageAttachmentPayload = computed(() =>
  (roomIDToChangeImageAttachment.value === null ? null : ({
    hotelID: props.hotel,
    roomID: roomIDToChangeImageAttachment.value,
    imageID: props.image.id,
  })))

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

const changeImageAttachment = async (roomID: HotelRoomID, value: boolean) => {
  // TODO revert checkbox state to previous if request is not success
  if (value) {
    roomIDToChangeImageAttachment.value = roomID
    await executeAttach()
    if (attachData.value !== null && attachData.value.success) {
      roomIDToChangeImageAttachment.value = null
      emit('update-rooms')
    }
  } else {
    roomIDToChangeImageAttachment.value = roomID
    await executeDetach()
    if (detachData.value !== null && detachData.value.success) {
      roomIDToChangeImageAttachment.value = null
      emit('update-rooms')
    }
  }
}

const isChangeImageAttachmentInProgress = computed<boolean>(() => (
  isAttachFetching.value || isDetachFetching.value || unref(props.isRoomsFetching)
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
    <div class="body">
      <div class="bodyForm">
        <div class="formTitle h6">Укажите, изображён ли здесь номер:</div>
        <ul class="roomsList list-group list-group-flush">
          <li
            v-for="room in rooms"
            :key="room.id"
            class="list-group-item"
          >
            <BootstrapCheckbox
              :label="room.customName"
              :value="isImageAttachedToRoom(image.id, room)"
              :disabled="isCheckboxDisabled"
              @input="value => changeImageAttachment(room.id, value)"
            />
          </li>
        </ul>
      </div>
      <div class="bodyImage">
        <BootstrapButton
          class="closeButton"
          label="Закрыть (Esc)"
          :only-icon="closeIcon"
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
</style>
