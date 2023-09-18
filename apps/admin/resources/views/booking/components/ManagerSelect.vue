<script setup lang="ts">

import { computed, onMounted, ref } from 'vue'

import { useAdministratorGetAPI } from '~api/administrator'

import Select2BaseSelect from '~components/Select2BaseSelect.vue'

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

const clientManagerSelect2 = ref()

const clearManagerComponentValue = () => {
  clientManagerSelect2.value.clearComponentValue()
}

onMounted(() => fetchAdministrators())

defineExpose({
  clearManagerComponentValue,
})

</script>

<template>
  <Select2BaseSelect
    id="client-manager"
    ref="clientManagerSelect2"
    label="Менеджер"
    :options="selectOptions"
    :value="localValue"
    :parent="parent"
    @input="val => $emit('input', val)"
  />
</template>
