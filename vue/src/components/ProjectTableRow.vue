<template>
  <tr>
    <td>{{ row.projectName }}</td>
    <td>
      <span class="flex items-center">
        <span class="mr-2 text-[0.6rem]" :class="{'status_open': row.codEstado == 0 , 'status_closed': row.codEstado > 0    }">{{ row.codEstado == 0 ? `Abierto` : `Cerrado` }}</span>
        <!-- <span class="mr-2">abierto</span> -->
        <img src="../assets/edit.svg" alt="" class="cursor-pointer" @click="openModal({param: 'editStatus', id: row.projectId})" />
      </span>
    </td>
    <td > ------- </td>
    <td>
      <span class="flex flex-col text-[0.6rem]">
        <span v-for="(equipment, index) in row.users" :key="index">{{
          equipment
        }}</span>
      </span>
    </td>

    <td>
      <span class="flex flex-col text-[0.68rem] text-orange">
        <span v-if="row.isInvitado == 0" class="cursor-pointer" @click="$emit('editProject', row.projectId)">Editar</span>
        <span class="cursor-pointer" @click="$emit('viewProject', row.id); openModal({param: 'viewproject', id: row.id})">Ver</span>
      </span>
    </td>
  </tr>
</template>

<script>
export default {
  name: "project-table-row-component",
  props: {
    row: Object,
    index: Number,
  },
  methods: {
    handleClick: function () {
    },
    openModal: function(payload) {
      this.$emit('openModal', {param: payload.param, id: payload.id});
    },
  },
};
</script>

<style>

.status_open{

background-color: #2f6fe3;
color: #fff;
padding: 5px;
border-radius: 1em;

}

.status_closed{

background-color: #bf2323;
color: #fff;
padding: 5px;
border-radius: 1em;

}

</style>
