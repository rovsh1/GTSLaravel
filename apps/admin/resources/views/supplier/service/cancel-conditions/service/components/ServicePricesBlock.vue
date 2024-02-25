<script setup lang="ts">

import { formatPeriod } from 'gts-common/helpers/date'

import CollapsableBlock from '~resources/views/hotel/settings/components/CollapsableBlock.vue'

import { Season } from '~api/models'
import { ExistCancelConditions } from '~api/supplier/cancel-conditions/service'

const props = defineProps<{
  header: string
  serviceId: number
  cancelConditions: ExistCancelConditions[] | undefined
  seasons: Season[]
}>()

const emit = defineEmits<{
  (event: 'click', serviceId: number, seasonId: number): void
}>()

const getButtonText = (seasonId: number): string => {
  const existCondition = props.cancelConditions?.find(
    (condition) => condition.season_id === seasonId,
  )
  return !existCondition ? 'Не установлено' : 'Изменить'
}

const handleClick = (seasonId: number): void => {
  emit('click', props.serviceId, seasonId)
}

</script>

<template>
  <CollapsableBlock :id="`service-cancel-conditions-${serviceId}`" :title="header" class="card-grid mb-3">
    <table class="table table-striped">
      <thead>
        <tr>
          <th v-for="season in seasons" :key="season.id" scope="col">
            {{ season.number }} ({{ formatPeriod(season) }})
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <template v-for="season in seasons" :key="season.id">
            <td>
              <a href="#" @click.prevent="handleClick(season.id)">
                {{ getButtonText(season.id) }}
              </a>
            </td>
          </template>
        </tr>
      </tbody>
    </table>
  </CollapsableBlock>
</template>
