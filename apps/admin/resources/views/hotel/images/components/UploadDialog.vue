<script lang="ts" setup>

import { ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { nanoid } from 'nanoid'
import prettyBytes from 'pretty-bytes'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'

import DropZone from './DropZone.vue'

import { SelectedFile } from './lib'

const props = withDefaults(defineProps<{
  opened: boolean
  loading?: MaybeRef<boolean>
  files: File[] | null
}>(), {
  loading: false,
})

const emit = defineEmits<{
  (event: 'files', value: File[]): void
  (event: 'upload'): void
  (event: 'close'): void
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

const mapFilesToDropped = async (files: File[]): Promise<SelectedFile[]> =>
  Promise.all(files.map(async (file) => ({
    id: nanoid(),
    name: file.name,
    size: prettyBytes(file.size),
    type: file.type,
    src: await blobToBase64(file),
    raw: file,
  })))

const selectedFiles = ref<SelectedFile[] | null>(null)

watch(selectedFiles, (files) => {
  if (files === null) {
    emit('files', [])
  } else {
    emit('files', files.map(({ raw }) => raw))
  }
})

watch(() => props.files, async (value) => {
  if (value === null) {
    selectedFiles.value = null
  }
})

const handleDropzoneFiles = async (files: File[] | null) => {
  if (files === null) {
    selectedFiles.value = files
  } else {
    const mapped = await mapFilesToDropped(files)
    selectedFiles.value = mapped
  }
}

const addFiles = async (files: File[] | null) => {
  if (files === null) return
  const mapped = await mapFilesToDropped(files)
  if (selectedFiles.value === null) {
    selectedFiles.value = mapped
  } else {
    selectedFiles.value.push(...mapped)
  }
}

const handleFileInput = async (event: Event) => {
  const input = event.target as HTMLInputElement
  const { files } = input
  if (files === null) return
  await addFiles(Array.from(files))
  input.value = ''
}
</script>
<template>
  <BaseDialog
    :opened="opened"
    :disabled="loading"
    @close="emit('close')"
  >
    <template #title>
      <div>Добавить фотографии</div>
    </template>
    <div class="upload">
      <DropZone
        :value="selectedFiles"
        :disabled="loading"
        @input="files => handleDropzoneFiles(files)"
      />
    </div>
    <template #actions-start>
      <div class="input">
        <label :for="instanceID" class="form-label visually-hidden">Или выберите их:</label>
        <input
          :id="instanceID"
          type="file"
          accept="image/jpeg"
          multiple
          :disabled="loading as boolean"
          class="form-control"
          @input="handleFileInput"
        >
      </div>
    </template>
    <template #actions-end>
      <BootstrapButton
        severity="primary"
        variant="filled"
        label="Загрузить"
        :loading="loading"
        @click="emit('upload')"
      />
    </template>
  </BaseDialog>
</template>
