<script lang="ts" setup>
import { ref, watch } from 'vue'

import crossIcon from '@mdi/svg/svg/close.svg'
import { useDropZone } from '@vueuse/core'
import { nanoid } from 'nanoid'
import prettyBytes from 'pretty-bytes'

import BaseButton from '~resources/components/BaseButton.vue'

type DroppedFile = {
  id: string
  name: string
  size: string
  type: string
  src: string
  raw: File
}

const props = withDefaults(defineProps<{
  value: File[] | null
  disabled?: boolean
}>(), {
  disabled: false,
})

const emit = defineEmits<{
  (event: 'input', files: File[]): void
}>()

const instanceID = nanoid()

const blobToBase64 = (blob: Blob): Promise<string> => {
  const reader = new FileReader()
  reader.readAsDataURL(blob)
  return new Promise((resolve) => {
    reader.onloadend = () => {
      const { result } = reader
      if (typeof result === 'string') resolve(result)
    }
  })
}

const selectedFiles = ref<DroppedFile[] | null>(null)

watch(selectedFiles, (files) => {
  if (files === null) {
    emit('input', [])
    return
  }
  emit('input', files.map(({ raw }) => raw))
})

const mapFilesToDropped = async (files: File[]): Promise<DroppedFile[]> =>
  Promise.all(files.map(async (file) => ({
    id: nanoid(),
    name: file.name,
    size: prettyBytes(file.size),
    type: file.type,
    src: await blobToBase64(file),
    raw: file,
  })))

const handleFiles = async (files: File[] | null) => {
  if (files === null) return
  const mapped = await mapFilesToDropped(files)
  if (selectedFiles.value === null) {
    selectedFiles.value = mapped
  } else {
    selectedFiles.value.push(...mapped)
  }
}

watch(() => props.value, async (value) => {
  if (value === null) {
    selectedFiles.value = null
  }
})

const onInput = async (event: Event) => {
  const input = event.target as HTMLInputElement
  const { files } = input
  if (files === null) return
  await handleFiles(Array.from(files))
  input.value = ''
}

const dropZoneRef = ref<HTMLElement>()

const { isOverDropZone } = useDropZone(dropZoneRef, (files) => {
  if (props.disabled) return
  handleFiles(files)
})

const remove = (idToRemove: string) => {
  if (selectedFiles.value === null) return
  selectedFiles.value = selectedFiles.value.filter(({ id }) => id !== idToRemove)
}
</script>
<template>
  <div
    ref="dropZoneRef"
    class="dropzone"
    :class="{ isOver: !disabled && isOverDropZone }"
  >
    <div class="dropped">
      <div v-if="selectedFiles === null">
        Перетащите фото сюда
      </div>
      <div v-else class="items">
        <div
          v-for="({ name, size, src, id }, index) in selectedFiles"
          :key="index"
          class="item"
        >
          <div class="image">
            <BaseButton
              label="Убрать"
              :only-icon="crossIcon"
              severity="danger"
              variant="filled"
              size="tiny"
              class="removeButton"
              @click="remove(id)"
            />
            <img :src="src" :alt="name" class="picture">
            <div class="size">{{ size }}</div>
          </div>
          <div class="name">{{ name }}</div>
        </div>
      </div>
    </div>
    <div class="input">
      <label :for="instanceID">
        Или выберите их:
      </label>
      <input
        :id="instanceID"
        required
        type="file"
        accept="image/jpeg,image/png,image/webp"
        multiple
        :disabled="disabled"
        @input="onInput"
      >
    </div>
  </div>
</template>
<style lang="scss" scoped>
.dropzone {
  display: flex;
  flex-flow: column;
  gap: 1em;
  align-items: center;
  min-height: 10em;
  padding: 1em;
  border: 2px dashed lightgray;
  border-radius: 0.75em;

  &.isOver {
    border-color: blue;
  }
}

.dropped {
  flex-grow: 1;
  width: 100%;
}

.items {
  display: flex;
  flex-wrap: wrap;
  gap: 1em;
}

.item {
  width: 10em;
}

.image {
  position: relative;
  display: flex;
  flex-flow: column;
  gap: 0.5em;
}

.removeButton {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0.5em;
}

.size {
  position: absolute;
  bottom: 0;
  left: 0;
  margin: 0.5em;
  padding-inline: 0.25em;
  border-radius: 0.2em;
  background-color: rgba(black, 0.9);
  color: white;
  font-size: 0.8em;
}

.picture {
  object-fit: cover;
  object-position: center;
  max-width: 100%;
  border-radius: 0.75em;
  aspect-ratio: 1/1;
}

.name {
  overflow: hidden;
  max-width: 100%;
  font-size: 0.9em;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.input {
  display: flex;
  flex-shrink: 0;
  gap: 1em;
  align-items: center;
}
</style>
