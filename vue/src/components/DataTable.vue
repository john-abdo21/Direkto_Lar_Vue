<template>
  <table class="w-full">
    <thead class="bg-[#DCE4F9]">
      <tr :class="{ 'h-14': tableType === 'scroll' }">
        <th
          v-if="tableType === 'scroll'"
          class="px-4 py-3 rounded-tl-[7px] w-16 absolute left-0 bg-[#DCE4F9] h-14 flex"
        >
          <img
            src="../assets/full-screen.svg"
            class="cursor-pointer"
            @click="$emit('fullScreen', {frontId: frontId, phaseId: phaseId, frontName: frontName, phaseName: phaseName})"
            alt=""
          />
          <img
            src="../assets/add-row.svg"
            class="cursor-pointer w-[32px] ml-[4px]"
            @click="$emit('addRowModal', {frontId: frontId, phaseId: phaseId, frontName: frontName, phaseName: phaseName})"
            alt=""
          />
        </th>
        <th
          v-for="(col, key) in cols"
          :key="key"
          class="text-left w-[250px] text-[0.6rem] leading-6 py-2 px-4 last:rounded-tr-[7px] [&:nth-child(2)]:w-[100px]"
          :class="{
            hidden: tableType === 'scroll' && hideCols.indexOf(key) > -1,
            '[&:nth-child(2)]:w-28 [&:nth-child(5)]:w-44':
              tableType === 'scroll',
            'first:rounded-tl-[7px]': tableType !== 'scroll',
          }"
          :title="colsref[key]"
        >
          {{ col }}
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- <template v-for="row in rows">
        <slot v-bind="row"></slot>
      </template> -->
      <template  v-for="(row, index)  in rows " :key="index">

        <slot :row=row  :index=index></slot>

    </template>
    </tbody>
  </table>
</template>


<script>
import "../assets/css/reset.css"

export default {
  props: {
    tableType: String,
    cols: Object,
    colsref: Object,
    rows: Array,
    hideCols: Array,
    frontId: Number,
    phaseId: Number,
    frontName: String,
    phaseName: String,
  },
};
</script>

<style>
tbody tr td {
  font-weight: 500;
  font-size: 12px;
  color: #212530;
  padding: 20px 16px;
}
</style>
