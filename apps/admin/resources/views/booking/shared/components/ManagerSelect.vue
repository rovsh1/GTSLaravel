<script setup lang="ts">

import { computed, onMounted } from 'vue'

import SelectComponent from 'gts-components/Base/SelectComponent'

import { useAdministratorGetAPI } from '~api/administrator'

const props = defineProps<{
  value?: any
  parent?: any
}>()

defineEmits<{
  (event: 'input', value: number | null): void
}>()

const { data: administrators, execute: fetchAdministrators } = useAdministratorGetAPI()

const localValue = computed(() => props.value)
const selectOptions = computed(
  () => administrators.value?.map(({ id, presentation }) => ({ value: id, label: presentation })) || [],
)

onMounted(() => fetchAdministrators())

</script>

<template>
  <SelectComponent
    :options="selectOptions"
    label="Менеджер"
    :returned-empty-value="null"
    :value="localValue"
    :allow-empty-item="true"
    @change="(value, event) => {
      $emit('input', value)
    }"
  />
</template>
