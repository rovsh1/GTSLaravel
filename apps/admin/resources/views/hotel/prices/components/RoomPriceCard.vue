<script lang="ts" setup>
import RoomPriceTable from './RoomPriceTable.vue'

const props = withDefaults(defineProps<{
  title: string
  data: any
}>(), {
  data: [],
})
</script>

<template>
  <div class="card" data-id="170">
    <div class="card-title">
      <h2>{{ title }}</h2>
    </div>
    <div class="card-body unselectable">
      <RoomPriceTable :data="{}" />

      <div class="card season-edit-price">
        <div class="card-body">
          <form method="post" class="form-labels form-inline">
            <div class="form-field field-daterange field-period field-required">
              <label for="form_data_period" class="form-element-label  field-daterange">Период <span class="required-label">*</span></label>
              <input id="form_data_period" type="text" name="data[period]" class=" field-daterange" autocomplete="off" value="">
            </div>
            <div class="form-field field-select field-days field-required">
              <label for="form_data_days" class="form-element-label  field-select">Выберите дни недели <span class="required-label">*</span></label>
              <div class="multiselect">
                <div class="value">
                  <span class="label">Выбрано 7 из 7</span>
                  <span class="select">Снять выделение</span>
                </div>
                <div class="popup">
                  <div class="item selected" data-value="1">Понедельник</div>
                  <div class="item selected" data-value="2">Вторник</div>
                  <div class="item selected" data-value="3">Среда</div>
                  <div class="item selected" data-value="4">Четверг</div>
                  <div class="item selected" data-value="5">Пятница</div>
                  <div class="item selected" data-value="6">Суббота</div>
                  <div class="item selected" data-value="7">Воскресенье</div>
                </div>
              </div>
              <select id="form_data_days" name="data[days][]" class=" field-select" multiple style="display: none;">
                <option value="1">Понедельник</option>
                <option value="2">Вторник</option>
                <option value="3">Среда</option>
                <option value="4">Четверг</option>
                <option value="5">Пятница</option>
                <option value="6">Суббота</option>
                <option value="7">Воскресенье</option>
              </select>
            </div>
            <div class="form-field field-number field-price field-required">
              <label for="form_data_price" class="form-element-label  field-number">Цена <span class="required-label">*</span></label>
              <input id="form_data_price" type="text" name="data[price]" class=" field-number" value="">
            </div>
            <button type="submit" class="btn btn-submit">Обновить</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
.card {
  margin-bottom: 16px;

  .card-title {
    padding: 16px 16px 0;

    h2 {
      position: relative;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0 9px 16px;
      border-bottom: 1px solid #f2f2f2;
      font-size: 16px;

      &::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 32px;
        height: 3px;
        background: #2584FA;
      }
    }
  }

  .card-body {
    padding-top: 0;

    table {
      caption {
        caption-side:top;
        font-weight: bold;
      }

      th {
        font-weight: normal;
      }
    }
  }
}

</style>
