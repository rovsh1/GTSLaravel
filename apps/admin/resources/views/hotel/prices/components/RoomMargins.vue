<script lang="ts" setup>
import EditableNumberInput from '~components/Editable/EditableNumberInput.vue'
</script>

<template>
  <div class="caption">Скидки и наценки</div>
  <div class="room-margins-table">
    <table :id="'id'" class="table table-sm">
      <tbody>
        <tr>
          <td class="text-left align-middle">
            <div class="content-wrapper">
              <span>Скидка</span>
              <div>
                <EditableNumberInput :value="1" dimension="%" />
              </div>
            </div>
          </td>
          <td class="text-left align-middle">
            <div class="content-wrapper">
              <span>Физическое лицо</span>
              <EditableNumberInput :value="1" dimension="%" />
            </div>
          </td>

          <td class="text-left align-middle">
            <div class="content-wrapper">
              <span>ОТА</span>
              <EditableNumberInput :value="1" dimension="%" />
            </div>
          </td>

          <td class="text-left align-middle">
            <div class="content-wrapper">
              <span>ТА</span>
              <EditableNumberInput :value="1" dimension="%" />
            </div>
          </td>

          <td class="text-left align-middle">
            <div class="content-wrapper">
              <span>ТО</span>
              <EditableNumberInput :value="1" dimension="%" />
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style lang="scss" scoped>
table {
  margin-bottom: 0;

  td {
    padding: 9px;
    border: none;
    font-size: 13px;
    text-align: center;

    .content-wrapper {
      display: flex;
      align-items: center;

      span {
        margin-right: 10px;
      }

      a {
        color: #000;
      }
    }
  }
}
</style>
