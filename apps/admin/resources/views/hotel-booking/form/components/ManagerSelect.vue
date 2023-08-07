<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { useAdministratorGetAPI } from '~api/administrator'

import { SelectedValue } from '~components/Bootstrap/lib'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const props = defineProps<{
  value?: number
  parent?: any
}>()

defineEmits<{
  (event: 'input', value: SelectedValue): void
}>()

const { data: administrators, execute: fetchAdministrators } = useAdministratorGetAPI()

const localValue = computed(() => props.value)
const selectOptions = computed(
  () => administrators.value?.map(({ id, presentation }) => ({ value: id, label: presentation })) || [],
)

onMounted(() => fetchAdministrators())

</script>

<template>
  <Select2BaseSelect
    id="client-manager"
    label="Менеджер"
    :options="selectOptions"
    :value="localValue"
    :parent="parent"
    @input="val => $emit('input', val)"
  />
</template>
