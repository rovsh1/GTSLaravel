<script setup lang="ts">

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'

const addCancellationCondition = (): void => {
  // @todo логика с модалкой
  console.log(123)
}

</script>

<template>
  <CollapsableBlock id="cancellation-conditions" title="Условия отмены">
    <template #header-controls>
      <button type="button" class="btn btn-add" @click="addCancellationCondition">
        <i class="icon">edit</i>
        Редактировать
      </button>
    </template>

    Условия отмены 123
  </CollapsableBlock>
</template>
